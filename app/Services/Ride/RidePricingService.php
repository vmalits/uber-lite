<?php

declare(strict_types=1);

namespace App\Services\Ride;

use App\Models\Ride;
use Carbon\CarbonInterface;

final class RidePricingService
{
    public function calculateFinalPrice(Ride $ride, CarbonInterface $completedAt): float
    {
        // Calculate actual duration (minimum 1 minute)
        $durationMin = 1;
        if ($ride->started_at instanceof CarbonInterface) {
            $durationMin = max(1, $ride->started_at->diffInMinutes($completedAt));
        }

        // Get distance (using estimate for now)
        $distanceKm = (float) $ride->estimated_distance_km;

        // Calculate price using ride-specific rates
        $price = $ride->base_fee + ($distanceKm * $ride->price_per_km) + ($durationMin * $ride->price_per_minute);

        // Price protection (prevent price decrease below estimated price)
        $price = max($price, $ride->estimated_price);

        // Round to the nearest 5 MDL
        return ceil($price / 5) * 5;
    }
}
