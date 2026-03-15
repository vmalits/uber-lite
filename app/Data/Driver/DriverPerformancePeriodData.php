<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Data;

final class DriverPerformancePeriodData extends Data
{
    public function __construct(
        public int $rides,
        public int $earnings,
        public int $tips,
        public float $rating,
        public float $completion_rate,
        public int $cancelled_rides,
        public int $online_hours,
    ) {}
}
