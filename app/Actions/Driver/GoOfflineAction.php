<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\DriverRealtimeLocationData;
use App\Enums\DriverAvailabilityStatus;
use App\Models\DriverSchedule;
use App\Models\User;
use App\Services\Driver\DriverLocationRedisStore;
use App\Services\Driver\DriverScheduleSyncService;
use Carbon\CarbonImmutable;

readonly class GoOfflineAction
{
    public function __construct(
        private DriverLocationRedisStore $redisStore,
        private DriverScheduleSyncService $syncService,
    ) {}

    public function handle(User $driver): DriverRealtimeLocationData
    {
        $this->redisStore->markOffline($driver->id);

        $this->setManualOverrideIfScheduled($driver);

        return new DriverRealtimeLocationData(
            driver_id: $driver->id,
            status: DriverAvailabilityStatus::OFFLINE,
            lat: null,
            lng: null,
            ts: time(),
        );
    }

    private function setManualOverrideIfScheduled(User $driver): void
    {
        $now = CarbonImmutable::now();
        $currentDay = $now->dayOfWeekIso % 7;
        $currentTime = $now->format('H:i:s');

        $activeSlot = DriverSchedule::query()
            ->where('driver_id', $driver->id)
            ->where('enabled', true)
            ->where('day_of_week', $currentDay)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>', $currentTime)
            ->first();

        if ($activeSlot === null) {
            return;
        }

        $endSeconds = $this->timeToSeconds($activeSlot->end_time);
        $nowSeconds = $this->timeToSeconds($currentTime);
        $ttl = max($endSeconds - $nowSeconds, 60);

        $this->syncService->setManualOverride($driver->id, $ttl);
    }

    private function timeToSeconds(string $time): int
    {
        $parts = explode(':', $time);

        return ((int) $parts[0] * 3600) + ((int) $parts[1] * 60) + ((int) ($parts[2] ?? 0));
    }
}
