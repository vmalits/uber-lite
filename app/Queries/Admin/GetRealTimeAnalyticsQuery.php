<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Dto\Admin\RealTimeAnalyticsData;
use App\Enums\DriverAvailabilityStatus;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\DriverLocation;
use App\Models\Ride;
use App\Models\User;
use App\Services\Ride\SurgePricingService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

final readonly class GetRealTimeAnalyticsQuery implements GetRealTimeAnalyticsQueryInterface
{
    private const int CACHE_TTL_SECONDS = 30;

    public function __construct(
        private SurgePricingService $surgeService,
    ) {}

    public function execute(): RealTimeAnalyticsData
    {
        /** @var RealTimeAnalyticsData $result */
        $result = Cache::remember(
            'analytics:realtime',
            self::CACHE_TTL_SECONDS,
            fn (): RealTimeAnalyticsData => $this->collect(),
        );

        return $result;
    }

    private function collect(): RealTimeAnalyticsData
    {
        return new RealTimeAnalyticsData(
            active_rides: $this->getActiveRidesCount(),
            pending_rides: $this->getPendingRidesCount(),
            online_drivers: $this->getOnlineDriversCount(),
            busy_drivers: $this->getBusyDriversCount(),
            total_riders_online: $this->getActiveRidersCount(),
            current_surge_multiplier: $this->surgeService->getCurrentSurge()->multiplier,
            rides_last_hour: $this->getRidesLastHour(),
            completed_rides_today: $this->getCompletedRidesToday(),
            cancelled_rides_today: $this->getCancelledRidesToday(),
            rides_by_status: $this->getRidesByStatus(),
            drivers_by_area: $this->getDriversByArea(),
        );
    }

    private function getActiveRidesCount(): int
    {
        return Ride::query()
            ->whereIn('status', [RideStatus::ACCEPTED, RideStatus::ARRIVED, RideStatus::STARTED])
            ->count();
    }

    private function getPendingRidesCount(): int
    {
        return Ride::query()
            ->where('status', RideStatus::PENDING)
            ->count();
    }

    private function getOnlineDriversCount(): int
    {
        return DriverLocation::query()
            ->where('status', DriverAvailabilityStatus::ONLINE)
            ->count();
    }

    private function getBusyDriversCount(): int
    {
        return DriverLocation::query()
            ->where('status', DriverAvailabilityStatus::BUSY)
            ->count();
    }

    private function getActiveRidersCount(): int
    {
        return User::query()
            ->where('role', UserRole::RIDER)
            ->where('last_login_at', '>=', now()->subMinutes(30))
            ->count();
    }

    private function getRidesLastHour(): int
    {
        return Ride::query()
            ->where('created_at', '>=', now()->subHour())
            ->count();
    }

    private function getCompletedRidesToday(): int
    {
        return Ride::query()
            ->where('status', RideStatus::COMPLETED)
            ->whereDate('completed_at', today())
            ->count();
    }

    private function getCancelledRidesToday(): int
    {
        return Ride::query()
            ->where('status', RideStatus::CANCELLED)
            ->whereDate('cancelled_at', today())
            ->count();
    }

    /**
     * @return array<string, int>
     */
    private function getRidesByStatus(): array
    {
        /** @var array<string, int> $result */
        $result = Ride::query()
            ->select('status', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subHours(24))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->map(fn (mixed $count): int => is_numeric($count) ? (int) $count : 0)
            ->all();

        return [
            'pending'    => $result[RideStatus::PENDING->value] ?? 0,
            'accepted'   => $result[RideStatus::ACCEPTED->value] ?? 0,
            'on_the_way' => $result[RideStatus::ON_THE_WAY->value] ?? 0,
            'arrived'    => $result[RideStatus::ARRIVED->value] ?? 0,
            'started'    => $result[RideStatus::STARTED->value] ?? 0,
            'completed'  => $result[RideStatus::COMPLETED->value] ?? 0,
            'cancelled'  => $result[RideStatus::CANCELLED->value] ?? 0,
        ];
    }

    /**
     * @return array<string, int>
     */
    private function getDriversByArea(): array
    {
        /** @var array<string, int> $result */
        $result = DriverLocation::query()
            ->select(DB::raw("
                CASE
                    WHEN lat BETWEEN 47.02 AND 47.05 AND lng BETWEEN 28.82 AND 28.86 THEN 'centru'
                    WHEN lat BETWEEN 46.98 AND 47.02 AND lng BETWEEN 28.84 AND 28.88 THEN 'botanica'
                    WHEN lat BETWEEN 47.03 AND 47.06 AND lng BETWEEN 28.79 AND 28.83 THEN 'buiucani'
                    ELSE 'other'
                END as area
            "), DB::raw('count(*) as count'))
            ->whereIn('status', [DriverAvailabilityStatus::ONLINE, DriverAvailabilityStatus::BUSY])
            ->groupBy('area')
            ->pluck('count', 'area')
            ->mapWithKeys(fn (mixed $count, mixed $area): array => \is_string($area) && is_numeric($count) ? [$area => (int) $count] : [])
            ->all();

        return $result;
    }
}
