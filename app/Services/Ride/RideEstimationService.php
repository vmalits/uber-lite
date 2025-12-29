<?php

declare(strict_types=1);

namespace App\Services\Ride;

use App\Data\PricingConfig;
use App\Data\Rider\CreateRideData;
use App\Data\Rider\RideEstimationData;

final class RideEstimationService
{
    private const int EARTH_RADIUS_KM = 6371;

    public function calculateEstimates(CreateRideData $data): RideEstimationData
    {
        $distanceKm = $this->calculateDistanceKm(
            $data->origin_lat,
            $data->origin_lng,
            $data->destination_lat,
            $data->destination_lng,
        );

        $distanceKm = max(0.3, min($distanceKm, 100));

        $durationMin = $this->calculateDurationMinutes($distanceKm);

        $price = $this->calculatePrice($distanceKm, $durationMin);

        return new RideEstimationData(
            distance: round($distanceKm, 2),
            duration: $durationMin,
            price: $price,
        );
    }

    private function calculateDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a =
            sin($latDelta / 2) ** 2 +
            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *
            sin($lonDelta / 2) ** 2;

        return self::EARTH_RADIUS_KM * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    private function calculateDurationMinutes(float $distanceKm): int
    {
        return (int) ceil($distanceKm / 30 * 60);
    }

    private function calculatePrice(float $distance, float $duration): float
    {
        $pricing = PricingConfig::fromConfig();

        $price =
            $pricing->baseFare
            + ($distance * $pricing->perKm)
            + ($duration * $pricing->perMinute);

        $price = max($price, $pricing->minimumFare);

        return ceil($price / 5.0) * 5.0;
    }
}
