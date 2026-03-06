<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\DailyRideStatsData;
use App\Data\Admin\RidesAnalyticsData;
use App\Enums\RideStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\DataCollection;

final readonly class GetRidesAnalyticsQuery implements GetRidesAnalyticsQueryInterface
{
    public function execute(int $days): RidesAnalyticsData
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Get overall stats
        $totalRides = DB::table('rides')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $completedRides = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $cancelledRides = DB::table('rides')
            ->where('status', RideStatus::CANCELLED->value)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Average price (completed rides only)
        /** @var float $averagePrice */
        $averagePrice = (float) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg('price');

        // Average distance (completed rides only)
        /** @var float $averageDistance */
        $averageDistance = (float) DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->avg('estimated_distance_km');

        // Average duration (completed rides only)
        $avgDurationResult = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('started_at')
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (completed_at - started_at))/60) as avg_duration')
            ->value('avg_duration');
        /** @var float $averageDuration */
        $averageDuration = is_numeric($avgDurationResult) ? (float) $avgDurationResult : 0.0;

        $cancellationRate = $totalRides > 0 ? round(($cancelledRides / $totalRides) * 100, 1) : 0.0;

        // Get daily stats
        $dailyStats = $this->getDailyStats($startDate, $endDate);

        return new RidesAnalyticsData(
            totalRides: $totalRides,
            completedRides: $completedRides,
            cancelledRides: $cancelledRides,
            averageRidePrice: round($averagePrice, 2),
            averageRideDistance: round($averageDistance, 2),
            averageRideDuration: round($averageDuration, 2),
            cancellationRate: $cancellationRate,
            dailyStats: new DataCollection(DailyRideStatsData::class, $dailyStats->all()),
        );
    }

    /**
     * @return Collection<int, DailyRideStatsData>
     */
    private function getDailyStats(Carbon $startDate, Carbon $endDate): Collection
    {
        /** @var Collection<int, object{date: string, total: int, completed: int, cancelled: int, revenue: int|string}> $stats */
        $stats = DB::table('rides')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as cancelled,
                COALESCE(SUM(CASE WHEN status = ? THEN price ELSE 0 END), 0) as revenue
            ', [RideStatus::COMPLETED->value, RideStatus::CANCELLED->value, RideStatus::COMPLETED->value])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Create a map of existing stats
        /** @var array<string, DailyRideStatsData> $statsMap */
        $statsMap = [];
        foreach ($stats as $stat) {
            $dateString = (string) $stat->date;
            $statsMap[$dateString] = new DailyRideStatsData(
                date: $dateString,
                totalRides: (int) $stat->total,
                completedRides: (int) $stat->completed,
                cancelledRides: (int) $stat->cancelled,
                revenue: (int) $stat->revenue,
            );
        }

        // Fill in missing days with zero stats
        $result = new Collection;
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $dateString = $current->toDateString();
            if (isset($statsMap[$dateString])) {
                $result->push($statsMap[$dateString]);
            } else {
                $result->push(new DailyRideStatsData(
                    date: $dateString,
                    totalRides: 0,
                    completedRides: 0,
                    cancelledRides: 0,
                    revenue: 0,
                ));
            }
            $current->addDay();
        }

        return $result;
    }
}
