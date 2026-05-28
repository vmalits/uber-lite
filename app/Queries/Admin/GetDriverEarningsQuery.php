<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\AdminDriverEarningsData;
use App\Enums\PayoutStatus;
use App\Enums\RideStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

final readonly class GetDriverEarningsQuery implements GetDriverEarningsQueryInterface
{
    public function execute(User $driver, ?string $from = null, ?string $to = null): AdminDriverEarningsData
    {
        $fromDate = $from !== null ? Carbon::parse($from) : Carbon::now()->subDays(30);
        $toDate = $to !== null ? Carbon::parse($to) : Carbon::now();

        $stats = $this->fetchStats($driver);
        $averageRating = $this->fetchAverageRating($driver);
        $payouts = $this->fetchPayoutTotals($driver);
        $daily = $this->fetchDailyEarnings($driver, $fromDate, $toDate);

        $completedRides = (int) ($stats->completed_rides ?? 0);
        $totalRides = (int) ($stats->total_rides ?? 0);

        return new AdminDriverEarningsData(
            totalEarned: round((float) ($stats->total_earned ?? 0), 2),
            totalRides: $totalRides,
            completedRides: $completedRides,
            cancelledRides: (int) ($stats->cancelled_rides ?? 0),
            completionRate: $totalRides > 0 ? round(($completedRides / $totalRides) * 100, 2) : 0.0,
            averageEarningsPerRide: round((float) ($stats->average_earnings_per_ride ?? 0), 2),
            averageRating: round($averageRating, 2),
            totalPayouts: (int) ($payouts->total_payouts ?? 0),
            pendingPayouts: (int) ($payouts->pending_payouts ?? 0),
            daily: $daily,
        );
    }

    /**
     * @return object{total_rides: int, completed_rides: int, cancelled_rides: int, average_earnings_per_ride: float, total_earned: float}|null
     */
    private function fetchStats(User $driver): ?object
    {
        /** @phpstan-ignore return.type */
        return DB::table('rides')
            ->where('driver_id', $driver->id)
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
    }

    private function fetchAverageRating(User $driver): float
    {
        return (float) (DB::table('ride_ratings')
            ->join('rides', 'rides.id', '=', 'ride_ratings.ride_id')
            ->where('rides.driver_id', $driver->id)
            ->avg('ride_ratings.rating') ?? 0.0);
    }

    /**
     * @return object{total_payouts: int, pending_payouts: int}|null
     */
    private function fetchPayoutTotals(User $driver): ?object
    {
        /** @phpstan-ignore return.type */
        return DB::table('driver_payouts')
            ->where('driver_id', $driver->id)
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN status = ? THEN amount ELSE 0 END), 0) as total_payouts,
                 COALESCE(SUM(CASE WHEN status IN (?, ?) THEN amount ELSE 0 END), 0) as pending_payouts',
                [
                    PayoutStatus::COMPLETED->value,
                    PayoutStatus::PENDING->value,
                    PayoutStatus::APPROVED->value,
                ],
            )
            ->first();
    }

    /**
     * @return array<int, array{date: string, total_rides: int, total_earnings: int, average_per_ride: float}>
     */
    private function fetchDailyEarnings(User $driver, Carbon $from, Carbon $to): array
    {
        /** @var array<int, object{date: string, total_rides: int, total_earnings: int}> $earnings */
        $earnings = DB::table('rides')
            ->where('driver_id', $driver->id)
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$from->startOfDay(), $to->endOfDay()])
            ->selectRaw(
                'DATE(completed_at) as date,
                  COUNT(*) as total_rides,
                  COALESCE(SUM(price), 0) as total_earnings',
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get()
            ->all();

        $result = [];

        foreach ($earnings as $earning) {
            $totalRides = $earning->total_rides;
            $totalEarnings = $earning->total_earnings;
            $averagePerRide = $totalRides > 0 ? round($totalEarnings / $totalRides, 2) : 0.0;

            $result[] = [
                'date'             => $earning->date,
                'total_rides'      => $totalRides,
                'total_earnings'   => $totalEarnings,
                'average_per_ride' => $averagePerRide,
            ];
        }

        return $result;
    }
}
