<?php

declare(strict_types=1);

namespace App\Jobs\Rider;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Support\RideStateMachine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class ActivateScheduledRideJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $rideId,
    ) {}

    public function handle(RideStateMachine $rideStateMachine): void
    {
        $ride = Ride::query()->find($this->rideId);

        if (! $ride || $ride->status !== RideStatus::SCHEDULED) {
            return;
        }

        $rideStateMachine->transition(
            ride: $ride,
            to: RideStatus::PENDING,
            actorType: ActorType::SYSTEM,
        );
    }
}
