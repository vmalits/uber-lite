<?php

declare(strict_types=1);

namespace App\Services\Ride;

use App\Dto\Rider\SurgeData;
use App\Enums\DriverAvailabilityStatus;
use App\Models\DriverLocation;
use App\Models\Ride;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;

final readonly class SurgePricingService
{
    private const int CACHE_TTL_SECONDS = 60;

    public function getCurrentSurge(): SurgeData
    {
        /** @var SurgeData $result */
        $result = Cache::remember(
            'surge:current',
            self::CACHE_TTL_SECONDS,
            fn (): SurgeData => $this->calculateSurge(),
        );

        return $result;
    }

    private function calculateSurge(): SurgeData
    {
        $now = now();

        $timeMultiplier = $this->getTimeBasedMultiplier($now);
        $demandMultiplier = $this->getDemandMultiplier();

        $totalMultiplier = round($timeMultiplier * $demandMultiplier, 2);

        if ($totalMultiplier <= 1.0) {
            return SurgeData::default();
        }

        $reason = $this->determineReason($timeMultiplier, $demandMultiplier);

        return new SurgeData(
            multiplier: $totalMultiplier,
            reason: $reason,
            is_active: true,
        );
    }

    private function getTimeBasedMultiplier(CarbonInterface $time): float
    {
        $hour = $time->hour;
        $isWeekend = $time->isWeekend();

        // Weekend evenings: 8 PM - 2 AM (20:00 - 01:59)
        if ($isWeekend && ($hour >= 20 || $hour < 2)) {
            return 1.4;
        }

        // Peak hours: 7-9 AM and 5-8 PM on weekdays
        if (! $isWeekend && (($hour >= 7 && $hour < 9) || ($hour >= 17 && $hour < 20))) {
            return 1.3;
        }

        // Late night: 11 PM - 4 AM
        if ($hour >= 23 || $hour < 4) {
            return 1.25;
        }

        return 1.0;
    }

    private function getDemandMultiplier(): float
    {
        $availableDrivers = DriverLocation::query()
            ->where('status', DriverAvailabilityStatus::ONLINE)
            ->count();

        // Active rides in last 15 minutes
        $activeRidesCount = Ride::query()
            ->whereIn('status', ['pending', 'accepted', 'arrived', 'in_progress'])
            ->where('created_at', '>=', now()->subMinutes(15))
            ->count();

        if ($availableDrivers === 0) {
            return 2.0;
        }

        $demandRatio = $activeRidesCount / $availableDrivers;

        if ($demandRatio >= 2.0) {
            return 1.5;
        }

        if ($demandRatio >= 1.5) {
            return 1.3;
        }

        if ($demandRatio >= 1.0) {
            return 1.15;
        }

        return 1.0;
    }

    private function determineReason(float $timeMultiplier, float $demandMultiplier): string
    {
        if ($demandMultiplier > 1.0 && $timeMultiplier > 1.0) {
            return 'high_demand_peak_hours';
        }

        if ($demandMultiplier > 1.0) {
            return 'high_demand';
        }

        if ($timeMultiplier > 1.0) {
            return 'peak_hours';
        }

        return 'normal';
    }
}
