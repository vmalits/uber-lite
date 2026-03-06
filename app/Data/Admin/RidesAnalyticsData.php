<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class RidesAnalyticsData extends Data
{
    /**
     * @param DataCollection<int, DailyRideStatsData> $dailyStats
     */
    public function __construct(
        #[MapName('total_rides')]
        public int $totalRides,
        #[MapName('completed_rides')]
        public int $completedRides,
        #[MapName('cancelled_rides')]
        public int $cancelledRides,
        #[MapName('average_ride_price')]
        public float $averageRidePrice,
        #[MapName('average_ride_distance')]
        public float $averageRideDistance,
        #[MapName('average_ride_duration')]
        public float $averageRideDuration,
        #[MapName('cancellation_rate')]
        public float $cancellationRate,
        /** @var DataCollection<int, DailyRideStatsData> */
        #[MapName('daily_stats')]
        public DataCollection $dailyStats,
    ) {}
}
