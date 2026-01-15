<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class RideEstimateData extends Data
{
    public function __construct(
        public float $distance_km,
        public int $duration_minutes,
        public int $price,
    ) {}
}
