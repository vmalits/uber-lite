<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class CancelScheduledRideAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private RideStateMachine $rideStateMachine,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(User $user, Ride $ride): Ride
    {
        return $this->databaseManager->transaction(
            callback: function () use ($user, $ride): Ride {
                $this->rideStateMachine->transition(
                    ride: $ride,
                    to: RideStatus::CANCELLED,
                    actorType: ActorType::RIDER,
                    actorId: $user->id,
                );

                return $ride->refresh();
            },
            attempts: 3,
        );
    }
}
