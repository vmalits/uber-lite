<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class DriverStatsData extends Data
{
    public function __construct(
        #[MapName('total_rides')]
        public int $totalRides,

        #[MapName('completed_rides')]
        public int $completedRides,

        #[MapName('cancelled_rides')]
        public int $cancelledRides,

        #[MapName('average_rating')]
        public float $averageRating,

        #[MapName('total_earned')]
        public float $totalEarned,
    ) {}
}
