<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Data;

final class DriverRatingSummaryData extends Data
{
    /**
     * @param array{5: int, 4: int, 3: int, 2: int, 1: int} $rating_distribution
     */
    public function __construct(
        public float $average_rating,
        public int $total_reviews,
        public array $rating_distribution,
        public float $platform_average,
        public int $percentile,
    ) {}
}
