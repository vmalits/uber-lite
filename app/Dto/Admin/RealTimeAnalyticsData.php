<?php

declare(strict_types=1);

namespace App\Dto\Admin;

use Spatie\LaravelData\Data;

final class RealTimeAnalyticsData extends Data
{
    public function __construct(
        public int $active_rides,
        public int $pending_rides,
        public int $online_drivers,
        public int $busy_drivers,
        public int $total_riders_online,
        public float $current_surge_multiplier,
        public int $rides_last_hour,
        public int $completed_rides_today,
        public int $cancelled_rides_today,
        /** @var array<string, int> */
        public array $rides_by_status,
        /** @var array<string, int> */
        public array $drivers_by_area,
    ) {}
}
