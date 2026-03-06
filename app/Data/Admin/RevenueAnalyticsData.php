<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class RevenueAnalyticsData extends Data
{
    /**
     * @param DataCollection<int, DailyRevenueData> $dailyRevenue
     * @param DataCollection<int, MonthlyRevenueData> $monthlyRevenue
     */
    public function __construct(
        #[MapName('total_revenue')]
        public int $totalRevenue,
        #[MapName('today_revenue')]
        public int $todayRevenue,
        #[MapName('week_revenue')]
        public int $weekRevenue,
        #[MapName('month_revenue')]
        public int $monthRevenue,
        #[MapName('average_daily_revenue')]
        public float $averageDailyRevenue,
        #[MapName('average_ride_price')]
        public float $averageRidePrice,
        #[MapName('total_discounts')]
        public int $totalDiscounts,
        #[MapName('growth_rate')]
        public float $growthRate,
        /** @var DataCollection<int, DailyRevenueData> */
        #[MapName('daily_revenue')]
        public DataCollection $dailyRevenue,
        /** @var DataCollection<int, MonthlyRevenueData> */
        #[MapName('monthly_revenue')]
        public DataCollection $monthlyRevenue,
    ) {}
}
