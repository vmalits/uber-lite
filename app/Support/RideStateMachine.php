<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\RideStatus;
use App\Enums\RideTransitions;
use App\Events\Rider\RideStatusChanged;
use App\Exceptions\Ride\InvalidRideTransition;
use App\Models\Ride;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Support\Facades\DB;

final readonly class RideStateMachine
{
    public function __construct(
        private EventsDispatcher $events,
    ) {}

    public function transition(
        Ride $ride,
        RideStatus $to,
        string $actorType,
        ?string $actorId = null,
    ): void {
        $from = $ride->status;

        if (! $this->canTransition($from, $to) && $from !== $to) {
            throw new InvalidRideTransition($from, $to);
        }

        DB::transaction(function () use ($ride, $from, $to, $actorType, $actorId) {
            if ($ride->status !== $to) {
                $ride->update([
                    'status' => $to,
                ]);
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
