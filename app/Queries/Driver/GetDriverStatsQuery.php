<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverStatsData;
use App\Enums\RideStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class GetDriverStatsQuery implements GetDriverStatsQueryInterface
{
    public function execute(User $user): DriverStatsData
    {
        $stats = DB::table('rides')
            ->where('driver_id', $user->id)
            ->selectRaw(
                'COUNT(*) as total_rides,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_rides,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as cancelled_rides,
                 AVG(CASE WHEN status = ? THEN price ELSE NULL END) as average_earnings_per_ride,
                 SUM(CASE WHEN status = ? THEN price ELSE 0 END) as total_earned',
                [
                    RideStatus::COMPLETED->value,
                    RideStatus::CANCELLED->value,
                    RideStatus::COMPLETED->value,
                    RideStatus::COMPLETED->value,
                ],
            )
            ->first();

        // Calculate average rating separately using a subquery
        $averageRating = DB::table('ride_ratings')
            ->join('rides', 'rides.id', '=', 'ride_ratings.ride_id')
            ->where('rides.driver_id', $user->id)
            ->avg('ride_ratings.rating');

        if ($stats === null) {
            return new DriverStatsData(
                totalRides: 0,
                completedRides: 0,
                cancelledRides: 0,
                completionRate: 0.0,
                averageRating: 0.0,
                averageEarningsPerRide: 0.0,
                totalEarned: 0.0,
            );
        }

        /** @var array<string, int|float|null> $statsArray */
        $statsArray = (array) $stats;

        $totalRides = (int) ($statsArray['total_rides'] ?? 0);
        $completedRides = (int) ($statsArray['completed_rides'] ?? 0);
        $cancelledRides = (int) ($statsArray['cancelled_rides'] ?? 0);
        $averageEarningsPerRide = round((float) ($statsArray['average_earnings_per_ride'] ?? 0.0), 2);
        $totalEarned = round((float) ($statsArray['total_earned'] ?? 0.0), 2);
        $averageRatingValue = round((float) ($averageRating ?? 0.0), 2);

        $completionRate = $totalRides > 0
            ? round(($completedRides / $totalRides) * 100, 2)
            : 0.0;

        return new DriverStatsData(
            totalRides: $totalRides,
            completedRides: $completedRides,
            cancelledRides: $cancelledRides,
            completionRate: $completionRate,
            averageRating: $averageRatingValue,
            averageEarningsPerRide: $averageEarningsPerRide,
            totalEarned: $totalEarned,
        );
    }
}
