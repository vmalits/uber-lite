<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\WeeklyEarningsData;
use App\Enums\RideStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class GetWeeklyEarningsQuery implements GetWeeklyEarningsQueryInterface
{
    public function execute(User $user, int $weeks = 4): WeeklyEarningsData
    {
        $startDate = Carbon::now()->subWeeks($weeks)->startOfWeek();
        $endDate = Carbon::now();

        $earnings = $this->fetchEarnings($user, $startDate, $endDate);
        $previousEarnings = $this->fetchEarnings($user, $startDate->copy()->subWeeks($weeks), $startDate);

        /** @var int $totalEarnings */
        $totalEarnings = $earnings->sum('price');
        $totalRides = $earnings->count();
        $onlineHours = $totalRides * 0.5;
        $averagePerHour = $onlineHours > 0 ? round($totalEarnings / $onlineHours, 2) : 0.0;

        $bestDay = $this->findBestDay($earnings);
        /** @var int $previousTotal */
        $previousTotal = $previousEarnings->sum('price');
        $comparison = $this->calculateComparison($totalEarnings, $previousTotal);

        return WeeklyEarningsData::fromArray([
            'total_earnings'         => $totalEarnings,
            'total_rides'            => $totalRides,
            'average_per_hour'       => $averagePerHour,
            'best_day'               => $bestDay['date'],
            'best_day_earnings'      => $bestDay['earnings'],
            'comparison_to_previous' => $comparison,
        ]);
    }

    /**
     * @return Collection<int, object{price: int, ride_date: string}>
     */
    private function fetchEarnings(User $user, Carbon $start, Carbon $end): Collection
    {
        /** @var Collection<int, object{price: int, ride_date: string}> $result */
        $result = DB::table('rides')
            ->where('driver_id', $user->id)
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$start, $end])
            ->selectRaw('price, DATE(completed_at) as ride_date')
            ->get();

        return $result;
    }

    /**
     * @param Collection<int, object{price: int, ride_date: string}> $earnings
     *
     * @return array{date: string|null, earnings: int}
     */
    private function findBestDay(Collection $earnings): array
    {
        if ($earnings->isEmpty()) {
            return ['date' => null, 'earnings' => 0];
        }

        /** @var Collection<string, int> $daily */
        $daily = $earnings->groupBy('ride_date')
            ->map(function (Collection $rides): int {
                /** @var int $sum */
                $sum = $rides->sum('price');

                return $sum;
            });

        /** @var string|null $bestDate */
        $bestDate = $daily->sortDesc()->keys()->first();

        if ($bestDate === null) {
            return ['date' => null, 'earnings' => 0];
        }

        /** @var int $bestEarnings */
        $bestEarnings = $daily[$bestDate];

        return [
            'date'     => $bestDate,
            'earnings' => $bestEarnings,
        ];
    }

    private function calculateComparison(int $current, int $previous): float
    {
        if ($previous === 0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
