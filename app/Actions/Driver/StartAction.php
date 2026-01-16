<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class StartAction
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
                    ->where('status', RideStatus::ARRIVED)
                    ->where('driver_id', $driverId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $lockedRide->update([
                    'started_at'       => now(),
                    'price_per_km'     => config('pricing.fixed_rates.per_km'),
                    'price_per_minute' => config('pricing.fixed_rates.per_minute'),
                    'base_fee'         => config('pricing.fixed_rates.base_fee'),
                ]);

                $this->stateMachine->transition(
                    ride: $lockedRide,
                    to: RideStatus::STARTED,
                    actorType: ActorType::DRIVER,
                    actorId: $driverId,
                );
            }, attempts: 3);
    }
}
