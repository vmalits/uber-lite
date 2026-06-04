<?php

declare(strict_types=1);

namespace App\Services\Matching;

use App\Models\BlockedDriver;
use App\Queries\Driver\FindNearbyDriversQueryInterface;
use denis660\Centrifugo\Centrifugo;
use Illuminate\Support\Facades\Log;
use Throwable;

final readonly class OrderMatchingService
{
    public function __construct(
        private FindNearbyDriversQueryInterface $nearbyDrivers,
        private Centrifugo $centrifugo,
    ) {}

    /**
     * @return array<int, string>
     */
    public function dispatchRide(
        string $rideId,
        float $pickupLat,
        float $pickupLng,
        string $riderId,
        int $limit = 10,
        ?float $radiusKm = null,
    ): array {
        $candidates = $this->nearbyDrivers->execute(
            lng: $pickupLng,
            lat: $pickupLat,
            radiusKm: $radiusKm,
            limit: $limit,
        );

        if ($candidates === []) {
            return [];
        }

        $blockedDriverIds = BlockedDriver::query()
            ->where('rider_id', $riderId)
            ->pluck('driver_id')
            ->toArray();

        $payload = [
            'event' => 'ride.offer',
            'data'  => [
                'ride_id'    => $rideId,
                'pickup_lat' => $pickupLat,
                'pickup_lng' => $pickupLng,
            ],
        ];

        $dispatched = [];

        foreach ($candidates as $candidate) {
            $driverId = $candidate['driver_id'];

            if (\in_array($driverId, $blockedDriverIds, true)) {
                continue;
            }

            try {
                $this->centrifugo->publish("driver:{$driverId}", $payload, true);
            } catch (Throwable $exception) {
                Log::warning('Failed to publish ride offer to driver.', [
                    'driver_id' => $driverId,
                    'ride_id'   => $rideId,
                    'error'     => $exception->getMessage(),
                ]);

                continue;
            }
            $dispatched[] = $driverId;
        }

        return $dispatched;
    }
}
