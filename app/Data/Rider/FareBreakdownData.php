<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class FareBreakdownData extends Data
{
    public function __construct(
        public float $base_fare,
        public float $distance_fare,
        public float $duration_fare,
        public float $total,
        public float $estimated_distance_km,
        public float $estimated_duration_min,
        public float $price_per_km,
        public float $price_per_minute,
    ) {}
}
