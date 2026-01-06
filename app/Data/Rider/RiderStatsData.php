<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class RiderStatsData extends Data
{
    public function __construct(
        #[MapName('total_rides')]
        public int $totalRides,

        #[MapName('completed_rides')]
        public int $completedRides,

        #[MapName('cancelled_rides')]
        public int $cancelledRides,

        #[MapName('completion_rate')]
        public float $completionRate,

        #[MapName('average_price')]
        public float $averagePrice,

        #[MapName('total_spent')]
        public float $totalSpent,
    ) {}
}
