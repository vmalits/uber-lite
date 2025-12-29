<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class RideEstimationData extends Data
{
    public function __construct(
        public float $distance,
        public float $duration,
        public float $price,
    ) {}
}
