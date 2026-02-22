<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Data;

final class HeatmapPointData extends Data
{
    public function __construct(
        public float $lat,
        public float $lng,
        public int $intensity,
        public float $weight,
    ) {}
}
