<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class OnTheWayAction
{
    public function __construct(
        private RideStateMachine $stateMachine,
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Ride $ride, string $driverId): void
    {
        $this->databaseManager->transaction(
            callback: function () use ($ride, $driverId) {
                /** @var Ride $lockedRide */
                $lockedRide = Ride::query()
                    ->where('id', $ride->id)
                    ->where('status', RideStatus::ACCEPTED)
                    ->where('driver_id', $driverId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->stateMachine->transition(
                    ride: $lockedRide,
                    to: RideStatus::ON_THE_WAY,
                    actorType: ActorType::DRIVER,
                    actorId: $driverId,
                );
            }, attempts: 3);
    }
}
