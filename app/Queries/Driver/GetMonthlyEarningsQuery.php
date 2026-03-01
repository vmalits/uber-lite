<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\MonthlyEarningsData;
use App\Enums\RideStatus;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final readonly class GetMonthlyEarningsQuery implements GetMonthlyEarningsQueryInterface
{
    public function execute(User $user, int $months = 3): MonthlyEarningsData
    {
        $startDate = \Illuminate\Support\Facades\Date::now()->subMonths($months)->startOfMonth();
        $endDate = \Illuminate\Support\Facades\Date::now();

        $earnings = $this->fetchEarnings($user, $startDate, $endDate);
        $previousEarnings = $this->fetchEarnings($user, $startDate->copy()->subMonths($months), $startDate);

        /** @var int $totalEarnings */
        $totalEarnings = $earnings->sum('price');
        $totalRides = $earnings->count();
        $onlineHours = $totalRides * 0.5;
        $averagePerHour = $onlineHours > 0 ? round($totalEarnings / $onlineHours, 2) : 0.0;

        $bestMonth = $this->findBestMonth($earnings);
        /** @var int $previousTotal */
        $previousTotal = $previousEarnings->sum('price');
        $comparison = $this->calculateComparison($totalEarnings, $previousTotal);

        return MonthlyEarningsData::fromArray([
            'total_earnings'         => $totalEarnings,
            'total_rides'            => $totalRides,
            'average_per_hour'       => $averagePerHour,
            'best_month'             => $bestMonth['month'],
            'best_month_earnings'    => $bestMonth['earnings'],
            'comparison_to_previous' => $comparison,
        ]);
    }

    /**
     * @return Collection<int, object{price: int, ride_month: string}>
     */
    private function fetchEarnings(User $user, CarbonInterface $start, CarbonInterface $end): Collection
    {
        /** @var Collection<int, object{price: int, ride_month: string}> $result */
        $result = DB::table('rides')
            ->where('driver_id', $user->id)
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$start, $end])
            ->selectRaw('price, TO_CHAR(completed_at, \'YYYY-MM\') as ride_month')
            ->get();

        return $result;
    }

    /**
     * @param Collection<int, object{price: int, ride_month: string}> $earnings
     *
     * @return array{month: string|null, earnings: int}
     */
    private function findBestMonth(Collection $earnings): array
    {
        if ($earnings->isEmpty()) {
            return ['month' => null, 'earnings' => 0];
        }

        /** @var Collection<string, int> $monthly */
        $monthly = $earnings->groupBy('ride_month')
            ->map(function (Collection $rides): int {
                /** @var int $sum */
                $sum = $rides->sum('price');

                return $sum;
            });

        /** @var string|null $bestMonth */
        $bestMonth = $monthly->sortDesc()->keys()->first();

        if ($bestMonth === null) {
            return ['month' => null, 'earnings' => 0];
        }

        /** @var int $bestEarnings */
        $bestEarnings = $monthly[$bestMonth];

        return [
            'month'    => $bestMonth,
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
