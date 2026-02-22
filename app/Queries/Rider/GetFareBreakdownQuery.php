<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\FareBreakdownData;
use App\Models\Ride;

final class GetFareBreakdownQuery implements GetFareBreakdownQueryInterface
{
    public function execute(Ride $ride): FareBreakdownData
    {
        $baseFeeConfig = config('pricing.estimation.base_fare', 15.0);
        if (is_numeric($ride->base_fee)) {
            $baseFee = (float) $ride->base_fee;
        } elseif (is_numeric($baseFeeConfig)) {
            $baseFee = (float) $baseFeeConfig;
        } else {
            $baseFee = 15.0;
        }

        $pricePerKmConfig = config('pricing.estimation.per_km', 8.0);
        if (is_numeric($ride->price_per_km)) {
            $pricePerKm = (float) $ride->price_per_km;
        } elseif (is_numeric($pricePerKmConfig)) {
            $pricePerKm = (float) $pricePerKmConfig;
        } else {
            $pricePerKm = 8.0;
        }

        $pricePerMinuteConfig = config('pricing.estimation.per_minute', 2.0);
        if (is_numeric($ride->price_per_minute)) {
            $pricePerMinute = (float) $ride->price_per_minute;
        } elseif (is_numeric($pricePerMinuteConfig)) {
            $pricePerMinute = (float) $pricePerMinuteConfig;
        } else {
            $pricePerMinute = 2.0;
        }

        $distanceKm = is_numeric($ride->estimated_distance_km)
            ? (float) $ride->estimated_distance_km
            : 0.0;
        $durationMin = is_numeric($ride->estimated_duration_min)
            ? (float) $ride->estimated_duration_min
            : 0.0;

        $distanceFare = $distanceKm * $pricePerKm;
        $durationFare = $durationMin * $pricePerMinute;

        $total = is_numeric($ride->estimated_price)
            ? (float) $ride->estimated_price
            : ($baseFee + $distanceFare + $durationFare);

        return new FareBreakdownData(
            base_fare: $baseFee,
            distance_fare: round($distanceFare, 2),
            duration_fare: round($durationFare, 2),
            total: $total,
            estimated_distance_km: $distanceKm,
            estimated_duration_min: $durationMin,
            price_per_km: $pricePerKm,
            price_per_minute: $pricePerMinute,
        );
    }
}
