<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\DailyRevenueData;
use App\Data\Admin\MonthlyRevenueData;
use App\Data\Admin\RevenueAnalyticsData;
use App\Enums\RideStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\DataCollection;

final readonly class GetRevenueAnalyticsQuery implements GetRevenueAnalyticsQueryInterface
{
    public function execute(int $days): RevenueAnalyticsData
    {
        $today = Carbon::today();
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfDay();

        // Total revenue (all time)
        /** @var int $totalRevenue */
        $totalRevenue = (int) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->sum('price');

        // Today's revenue
        /** @var int $todayRevenue */
        $todayRevenue = (int) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereDate('completed_at', '>=', $today)
            ->sum('price');

        // This week's revenue
        /** @var int $weekRevenue */
        $weekRevenue = (int) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereDate('completed_at', '>=', $weekStart)
            ->sum('price');

        // This month's revenue
        /** @var int $monthRevenue */
        $monthRevenue = (int) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereDate('completed_at', '>=', $monthStart)
            ->sum('price');

        // Average daily revenue
        $averageDailyRevenue = $this->calculateAverageDailyRevenue($startDate);

        // Average ride price
        /** @var float $averageRidePrice */
        $averageRidePrice = (float) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->avg('price');

        // Total discounts given
        /** @var int $totalDiscounts */
        $totalDiscounts = (int) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->sum('discount_amount');

        // Growth rate (compare current period to previous period)
        $growthRate = $this->calculateGrowthRate($days);

        // Daily revenue breakdown
        $dailyRevenue = $this->getDailyRevenue($startDate, $endDate);

        // Monthly revenue breakdown (last 12 months)
        $monthlyRevenue = $this->getMonthlyRevenue();

        return new RevenueAnalyticsData(
            totalRevenue: $totalRevenue,
            todayRevenue: $todayRevenue,
            weekRevenue: $weekRevenue,
            monthRevenue: $monthRevenue,
            averageDailyRevenue: round($averageDailyRevenue, 2),
            averageRidePrice: round($averageRidePrice, 2),
            totalDiscounts: $totalDiscounts,
            growthRate: $growthRate,
            dailyRevenue: new DataCollection(DailyRevenueData::class, $dailyRevenue->all()),
            monthlyRevenue: new DataCollection(MonthlyRevenueData::class, $monthlyRevenue->all()),
        );
    }

    private function calculateAverageDailyRevenue(Carbon $startDate): float
    {
        $endDate = Carbon::now()->endOfDay();

        $result = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->selectRaw('DATE(completed_at) as date, SUM(price) as daily_total')
            ->groupBy('date')
            ->get();

        if ($result->isEmpty()) {
            return 0.0;
        }

        /** @var int $totalSum */
        $totalSum = $result->sum('daily_total');

        return (float) $totalSum / $result->count();
    }

    private function calculateGrowthRate(int $days): float
    {
        $currentStart = Carbon::now()->subDays($days)->startOfDay();
        $currentEnd = Carbon::now()->endOfDay();
        $previousStart = Carbon::now()->subDays($days * 2)->startOfDay();
        $previousEnd = Carbon::now()->subDays($days)->endOfDay();

        /** @var int $currentRevenue */
        $currentRevenue = (int) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$currentStart, $currentEnd])
            ->sum('price');

        /** @var int $previousRevenue */
        $previousRevenue = (int) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$previousStart, $previousEnd])
            ->sum('price');

        if ($previousRevenue === 0) {
            return $currentRevenue > 0 ? 100.0 : 0.0;
        }

        return round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1);
    }

    /**
     * @return Collection<int, DailyRevenueData>
     */
    private function getDailyRevenue(Carbon $startDate, Carbon $endDate): Collection
    {
        /** @var Collection<int, object{date: string, revenue: int|string, rides: int}> $stats */
        $stats = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->selectRaw('DATE(completed_at) as date, SUM(price) as revenue, COUNT(*) as rides')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        /** @var array<string, DailyRevenueData> $statsMap */
        $statsMap = [];
        foreach ($stats as $stat) {
            $dateString = (string) $stat->date;
            $statsMap[$dateString] = new DailyRevenueData(
                date: $dateString,
                revenue: (int) $stat->revenue,
                rides: (int) $stat->rides,
            );
        }

        $result = new Collection;
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dateString = $current->toDateString();
            if (isset($statsMap[$dateString])) {
                $result->push($statsMap[$dateString]);
            } else {
                $result->push(new DailyRevenueData(
                    date: $dateString,
                    revenue: 0,
                    rides: 0,
                ));
            }
            $current->addDay();
        }

        return $result;
    }

    /**
     * @return Collection<int, MonthlyRevenueData>
     */
    private function getMonthlyRevenue(): Collection
    {
        /** @var Collection<int, object{month: string, revenue: int|string, rides: int}> $stats */
        $stats = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->where('completed_at', '>=', Carbon::now()->subMonths(12)->startOfMonth())
            ->selectRaw("TO_CHAR(completed_at, 'YYYY-MM') as month, SUM(price) as revenue, COUNT(*) as rides")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $result = new Collection;
        foreach ($stats as $stat) {
            $result->push(new MonthlyRevenueData(
                month: (string) $stat->month,
                revenue: (int) $stat->revenue,
                rides: (int) $stat->rides,
            ));
        }

        return $result;
    }
}
