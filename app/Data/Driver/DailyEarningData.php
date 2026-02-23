<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class DailyEarningData extends Data
{
    public function __construct(
        public string $date,
        #[MapName('total_rides')]
        public int $totalRides,
        #[MapName('total_earnings')]
        public int $totalEarnings,
        #[MapName('average_per_ride')]
        public float $averagePerRide,
        #[MapName('online_minutes')]
        public int $onlineMinutes,
    ) {}
}
