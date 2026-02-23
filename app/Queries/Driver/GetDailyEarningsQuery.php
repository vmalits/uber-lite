<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DailyEarningData;
use App\Enums\RideStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final readonly class GetDailyEarningsQuery implements GetDailyEarningsQueryInterface
{
    /**
     * @return array<int, DailyEarningData>
     */
    public function execute(User $user, ?string $from = null, ?string $to = null): array
    {
        $fromDate = $from !== null ? Carbon::parse($from) : Carbon::now()->subDays(30);
        $toDate = $to !== null ? Carbon::parse($to) : Carbon::now();

        $earnings = DB::table('rides')
            ->where('driver_id', $user->id)
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ->selectRaw(
                'DATE(completed_at) as date,
                 COUNT(*) as total_rides,
                 COALESCE(SUM(price), 0) as total_earnings',
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        /** @var array<int, DailyEarningData> $result */
        $result = [];

        /** @var object{date: string, total_rides: int, total_earnings: int} $earning */
        foreach ($earnings as $earning) {
            $totalRides = $earning->total_rides;
            $totalEarnings = $earning->total_earnings;
            $averagePerRide = $totalRides > 0 ? round($totalEarnings / $totalRides, 2) : 0.0;

            $result[] = new DailyEarningData(
                date: $earning->date,
                totalRides: $totalRides,
                totalEarnings: $totalEarnings,
                averagePerRide: $averagePerRide,
                onlineMinutes: 0,
            );
        }

        return $result;
    }
}
