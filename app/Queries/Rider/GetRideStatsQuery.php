<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\RiderStatsData;
use App\Enums\RideStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class GetRideStatsQuery implements GetRideStatsQueryInterface
{
    public function execute(User $user): RiderStatsData
    {
        $stats = DB::table('rides')
            ->where('rider_id', $user->id)
            ->selectRaw(
                'COUNT(*) as total_rides,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as completed_rides,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as cancelled_rides,
                 AVG(price) as average_price,
                 SUM(price) as total_spent',
                [RideStatus::COMPLETED->value, RideStatus::CANCELLED->value],
            )
            ->first();

        if ($stats === null) {
            return new RiderStatsData(
                totalRides: 0,
                completedRides: 0,
                cancelledRides: 0,
                completionRate: 0.0,
                averagePrice: 0.0,
                totalSpent: 0.0,
            );
        }

        /** @var array<string, int|float|null> $statsArray */
        $statsArray = (array) $stats;

        $totalRides = (int) ($statsArray['total_rides'] ?? 0);
        $completedRides = (int) ($statsArray['completed_rides'] ?? 0);
        $cancelledRides = (int) ($statsArray['cancelled_rides'] ?? 0);
        $averagePrice = round((float) ($statsArray['average_price'] ?? 0.0), 2);
        $totalSpent = round((float) ($statsArray['total_spent'] ?? 0.0), 2);

        $completionRate = $totalRides > 0
            ? round(($completedRides / $totalRides) * 100, 2)
            : 0.0;

        return new RiderStatsData(
            totalRides: $totalRides,
            completedRides: $completedRides,
            cancelledRides: $cancelledRides,
            completionRate: $completionRate,
            averagePrice: $averagePrice,
            totalSpent: $totalSpent,
        );
    }
}
