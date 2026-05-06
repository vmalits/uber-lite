<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Exceptions\Ride\InvalidRideTransition;
use App\Models\Ride;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class AssignDriverAction
{
    public function __construct(
        private RideStateMachine $stateMachine,
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Ride $ride, string $driverId): Ride
    {
        if ($ride->status !== RideStatus::PENDING) {
            throw new InvalidRideTransition($ride->status, RideStatus::ACCEPTED);
        }

        if ($ride->driver_id !== null) {
            throw new InvalidRideTransition($ride->status, RideStatus::ACCEPTED);
        }

        $this->databaseManager->transaction(
            callback: function () use ($ride, $driverId): void {
                $ride->update(['driver_id' => $driverId]);

                $ride->generatePin();

                $this->stateMachine->transition(
                    ride: $ride,
                    to: RideStatus::ACCEPTED,
                    actorType: ActorType::ADMIN,
                );
            },
            attempts: 3,
        );

        return $ride->refresh();
    }
}
