<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\AnalyticsOverviewData;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final readonly class GetAnalyticsOverviewQuery implements GetAnalyticsOverviewQueryInterface
{
    public function execute(): AnalyticsOverviewData
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        // Daily Active Users (unique riders who completed rides today)
        $dau = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereDate('completed_at', '>=', $today)
            ->distinct('rider_id')
            ->count('rider_id');

        // Monthly Active Users (unique riders who completed rides this month)
        $mau = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereDate('completed_at', '>=', $monthStart)
            ->distinct('rider_id')
            ->count('rider_id');

        // Total Revenue (all time from completed rides)
        /** @var int $totalRevenue */
        $totalRevenue = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->sum('price');

        // Today's Revenue
        /** @var int $todayRevenue */
        $todayRevenue = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereDate('completed_at', '>=', $today)
            ->sum('price');

        // Total Rides (completed)
        $totalRides = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->count();

        // Today's Rides (completed)
        $todayRides = DB::table('rides')
            ->where('status', RideStatus::COMPLETED->value)
            ->whereDate('completed_at', '>=', $today)
            ->count();

        // Total Users (riders)
        $totalUsers = DB::table('users')
            ->where('role', UserRole::RIDER->value)
            ->count();

        // Total Drivers
        $totalDrivers = DB::table('users')
            ->where('role', UserRole::DRIVER->value)
            ->count();

        return new AnalyticsOverviewData(
            dailyActiveUsers: $dau,
            monthlyActiveUsers: $mau,
            totalRevenue: (int) $totalRevenue,
            todayRevenue: (int) $todayRevenue,
            totalRides: $totalRides,
            todayRides: $todayRides,
            totalUsers: $totalUsers,
            totalDrivers: $totalDrivers,
        );
    }
}
