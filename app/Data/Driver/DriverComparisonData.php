<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Data;

final class DriverComparisonData extends Data
{
    public function __construct(
        public int $percentile,
        public int $avg_rides,
        public int $avg_earnings,
        public float $avg_rating,
        public int $top_10_percent_rides,
        public int $top_10_percent_earnings,
    ) {}
}
