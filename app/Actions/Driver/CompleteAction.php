<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Services\Ride\RidePricingService;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class CompleteAction
{
    public function __construct(
        private RideStateMachine $stateMachine,
        private DatabaseManager $databaseManager,
        private RidePricingService $pricingService,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Ride $ride, string $driverId): void
    {
        $this->databaseManager->transaction(
            callback: function () use ($ride, $driverId) {
                $lockedRide = Ride::query()
                    ->where('id', $ride->id)
                    ->where('status', RideStatus::STARTED)
                    ->where('driver_id', $driverId)
                    ->lockForUpdate()
                    ->firstOrFail();

                $completedAt = now();

                $finalPrice = $this->pricingService->calculateFinalPrice($lockedRide, $completedAt);

                $ride->update([
                    'price'        => $finalPrice,
                    'completed_at' => $completedAt,
                ]);

                $this->stateMachine->transition(
                    ride: $ride,
                    to: RideStatus::COMPLETED,
                    actorType: ActorType::DRIVER,
                    actorId: $driverId,
                );
            },
            attempts: 3,
        );
    }
}
