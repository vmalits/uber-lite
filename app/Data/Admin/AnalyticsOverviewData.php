<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class AnalyticsOverviewData extends Data
{
    public function __construct(
        #[MapName('daily_active_users')]
        public int $dailyActiveUsers,
        #[MapName('monthly_active_users')]
        public int $monthlyActiveUsers,
        #[MapName('total_revenue')]
        public int $totalRevenue,
        #[MapName('today_revenue')]
        public int $todayRevenue,
        #[MapName('total_rides')]
        public int $totalRides,
        #[MapName('today_rides')]
        public int $todayRides,
        #[MapName('total_users')]
        public int $totalUsers,
        #[MapName('total_drivers')]
        public int $totalDrivers,
    ) {}
}
