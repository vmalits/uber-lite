<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Data;

final class DriverPerformanceData extends Data
{
    public function __construct(
        public DriverPerformancePeriodData $current_period,
        public DriverPerformancePeriodData $previous_period,
        public DriverComparisonData $comparison,
    ) {}
}
