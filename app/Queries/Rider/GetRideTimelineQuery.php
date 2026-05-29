<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\RideTimelineData;
use App\Data\Rider\RideTimelineEventData;
use App\Enums\RideStatus;
use App\Models\Ride;
use Spatie\LaravelData\DataCollection;

final class GetRideTimelineQuery implements GetRideTimelineQueryInterface
{
    public function execute(Ride $ride): RideTimelineData
    {
        $events = [];

        $this->addEvent($events, RideStatus::SCHEDULED, $ride->scheduled_at);
        $this->addEvent($events, RideStatus::PENDING, $ride->created_at);
        $this->addEvent($events, RideStatus::ACCEPTED, null);
        $this->addEvent($events, RideStatus::ON_THE_WAY, null);
        $this->addEvent($events, RideStatus::ARRIVED, $ride->arrived_at);
        $this->addEvent($events, RideStatus::STARTED, $ride->started_at);
        $this->addEvent($events, RideStatus::COMPLETED, $ride->completed_at);
        $this->addEvent($events, RideStatus::CANCELLED, $ride->cancelled_at);

        $events = $this->filterEvents($events, $ride->status);

        /** @var DataCollection<int, RideTimelineEventData> $eventCollection */
        $eventCollection = new DataCollection(RideTimelineEventData::class, $events);

        return new RideTimelineData(
            ride_id: $ride->id,
            current_status: $ride->status,
            events: $eventCollection,
        );
    }

    /**
     * @param array<int, RideTimelineEventData> $events
     */
    private function addEvent(array &$events, RideStatus $status, ?\Carbon\CarbonInterface $timestamp): void
    {
        $events[] = new RideTimelineEventData(
            status: $status,
            timestamp: $timestamp?->toDateTimeString(),
        );
    }

    /**
     * Keep only statuses that have been reached, plus the current status.
     * For cancelled rides, include all statuses up to the point of cancellation.
     *
     * @param array<int, RideTimelineEventData> $events
     *
     * @return array<int, RideTimelineEventData>
     */
    private function filterEvents(array $events, RideStatus $currentStatus): array
    {
        /** @var array<string, int> $order */
        $order = [
            RideStatus::SCHEDULED->value  => 0,
            RideStatus::PENDING->value    => 1,
            RideStatus::ACCEPTED->value   => 2,
            RideStatus::ON_THE_WAY->value => 3,
            RideStatus::ARRIVED->value    => 4,
            RideStatus::STARTED->value    => 5,
            RideStatus::COMPLETED->value  => 6,
            RideStatus::CANCELLED->value  => 7,
        ];

        $currentIndex = $order[$currentStatus->value];
        $hasScheduled = $currentStatus === RideStatus::SCHEDULED
            || ($currentIndex > 0 && $events[0]->timestamp !== null);

        $filtered = [];
        $started = false;

        foreach ($events as $event) {
            if ($event->status === RideStatus::SCHEDULED) {
                if ($hasScheduled) {
                    $filtered[] = $event;
                }

                continue;
            }

            if (! $started && $event->status === RideStatus::PENDING) {
                $started = true;
                $filtered[] = $event;

                continue;
            }

            if (! $started) {
                continue;
            }

            if ($event->status === RideStatus::CANCELLED) {
                if ($currentStatus === RideStatus::CANCELLED) {
                    $filtered[] = $event;
                }

                continue;
            }

            $eventIndex = $order[$event->status->value];

            if ($event->timestamp !== null || $eventIndex <= $currentIndex) {
                $filtered[] = $event;
            }
        }

        return $filtered;
    }
}
