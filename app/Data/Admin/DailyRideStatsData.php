<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class DailyRideStatsData extends Data
{
    public function __construct(
        public string $date,
        #[MapName('total_rides')]
        public int $totalRides,
        #[MapName('completed_rides')]
        public int $completedRides,
        #[MapName('cancelled_rides')]
        public int $cancelledRides,
        public int $revenue,
    ) {}
}
