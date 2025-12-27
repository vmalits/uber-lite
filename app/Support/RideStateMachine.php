<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Enums\RideTransitions;
use App\Events\Rider\RideStatusChanged;
use App\Exceptions\Ride\InvalidRideTransition;
use App\Models\Ride;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class RideStateMachine
{
    public function __construct(
        private EventsDispatcher $events,
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @param array<string, mixed> $meta
     *
     * @throws Throwable
     */
    public function transition(
        Ride $ride,
        RideStatus $to,
        ActorType $actorType,
        ?string $actorId = null,
        array $meta = [],
    ): void {
        $from = $ride->status;

        if (! $this->canTransition($from, $to) && $from !== $to) {
            throw new InvalidRideTransition($from, $to);
        }

        $this->databaseManager->transaction(function () use (
            $ride, $from, $to, $actorType, $actorId, $meta
        ) {
            if ($ride->status !== $to) {
                $update = ['status' => $to];

                if ($to === RideStatus::CANCELLED) {
                    $update += [
                        'cancelled_by_type' => $actorType,
                        'cancelled_by_id'   => $actorId,
                        'cancelled_reason'  => $meta['reason'] ?? null,
                        'cancelled_at'      => now(),
                    ];
                }

                $ride->update($update);
            }

            $this->events->dispatch(new RideStatusChanged(
                ride: $ride,
                from: $from,
                to: $to,
                actorType: $actorType,
                actorId: $actorId,
            ));
        });
    }

    public function canTransition(RideStatus $from, RideStatus $to): bool
    {
        return \in_array($to, RideTransitions::MAP[$from->value] ?? [], true);
    }
}
