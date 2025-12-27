<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Exceptions\Ride\InvalidRideTransition;
use App\Models\Ride;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class CancelRide
{
    public function __construct(
        private RideStateMachine $stateMachine,
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Ride $ride): Ride
    {
        return $this->databaseManager->transaction(
            callback: function () use ($ride): Ride {
                /** @var Ride|null $lockedRide */
                $lockedRide = Ride::query()
                    ->whereKey($ride->id)
                    ->where('driver_id', $ride->driver_id)
                    ->lockForUpdate()
                    ->first();

                if (! $lockedRide) {
                    throw ValidationException::withMessages([
                        'ride' => ['Ride not found or not assigned to this driver.'],
                    ]);
                }

                if (! $lockedRide->status->isActive()) {
                    throw ValidationException::withMessages([
                        'ride' => ['Ride cannot be cancelled in its current status.'],
                    ]);
                }

                try {
                    $this->stateMachine->transition(
                        ride: $lockedRide,
                        to: RideStatus::CANCELLED,
                        actorType: ActorType::DRIVER,
                        actorId: $lockedRide->driver_id,
                    );
                } catch (InvalidRideTransition) {
                    throw ValidationException::withMessages([
                        'ride' => ['Invalid ride status transition.'],
                    ]);
                }

                return $lockedRide->refresh();
            },
            attempts: 3,
        );
    }
}
