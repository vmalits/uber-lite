<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Exceptions\Ride\InvalidRideTransition;
use App\Models\Ride;
use App\Support\RideStateMachine;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class CancelRide
{
    public function __construct(
        private RideStateMachine $stateMachine,
    ) {}

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function handle(Ride $ride): Ride
    {
        try {
            $this->stateMachine->transition(
                ride: $ride,
                to: RideStatus::CANCELLED,
                actorType: ActorType::RIDER,
                actorId: $ride->rider_id,
            );

            return $ride->refresh();
        } catch (InvalidRideTransition) {
            throw ValidationException::withMessages([
                'ride' => ['Ride cannot be cancelled in its current status.'],
            ]);
        }
    }
}
