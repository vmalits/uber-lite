<?php

declare(strict_types=1);

namespace App\Services\Driver;

use App\Enums\DriverAvailabilityStatus;
use App\Models\DriverSchedule;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

final readonly class DriverScheduleSyncService
{
    public function __construct(
        private DriverLocationRedisStore $redisStore,
        private DriverLocationConfig $config,
        private RedisFactory $redis,
    ) {}

    public function syncAll(): SyncResult
    {
        $now = CarbonImmutable::now();
        $wentOnline = 0;
        $wentOffline = 0;

        $toOnline = $this->findDriversToBringOnline($now);
        foreach ($toOnline as $schedule) {
            $driverId = (string) $schedule->driver_id;
            if ($this->hasManualOverride($driverId)) {
                continue;
            }

            $this->bringOnline($driverId);
            $wentOnline++;

            Log::info('Driver brought online by schedule sync', [
                'driver_id'   => $driverId,
                'schedule_id' => $schedule->id,
                'day_of_week' => $schedule->day_of_week,
                'start_time'  => $schedule->start_time,
            ]);
        }

        $toOffline = $this->findDriversToSendOffline($now);
        foreach ($toOffline as $schedule) {
            $driverId = (string) $schedule->driver_id;
            $this->sendOffline($driverId);
            $wentOffline++;

            Log::info('Driver sent offline by schedule sync', [
                'driver_id'   => $driverId,
                'schedule_id' => $schedule->id,
                'day_of_week' => $schedule->day_of_week,
                'end_time'    => $schedule->end_time,
            ]);
        }

        return new SyncResult(
            wentOnline: $wentOnline,
            wentOffline: $wentOffline,
            candidatesOnline: $toOnline->count(),
            candidatesOffline: $toOffline->count(),
        );
    }

    public function getScheduleStatus(User $driver): ScheduleStatusResult
    {
        $now = CarbonImmutable::now();
        $currentDay = $now->dayOfWeekIso % 7;
        $currentTime = $now->format('H:i:s');

        $schedules = $driver->schedules()
            ->where('enabled', true)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $activeSlot = $schedules->first(
            fn (DriverSchedule $s): bool => $s->day_of_week === $currentDay
                && $s->start_time <= $currentTime
                && $s->end_time > $currentTime,
        );

        $nextSlot = $this->findNextSlot($schedules, $now);

        $driverId = (string) $driver->id;
        $hasOverride = $this->hasManualOverride($driverId);
        $overrideUntil = null;
        if ($hasOverride) {
            $ttl = $this->redis->connection()->command('ttl', [$this->manualOverrideKey($driverId)]);
            if (\is_int($ttl) && $ttl > 0) {
                $overrideUntil = $now->addSeconds($ttl)->toIso8601String();
            }
        }

        return new ScheduleStatusResult(
            activeSlot: $activeSlot,
            nextSlot: $nextSlot,
            manualOverride: $hasOverride,
            manualOverrideUntil: $overrideUntil,
        );
    }

    public function setManualOverride(string $driverId, int $ttlSeconds): void
    {
        $this->redis->connection()->command('setex', [
            $this->manualOverrideKey($driverId),
            $ttlSeconds,
            '1',
        ]);
    }

    public function clearManualOverride(string $driverId): void
    {
        $this->redis->connection()->command('del', [$this->manualOverrideKey($driverId)]);
    }

    public function hasManualOverride(string $driverId): bool
    {
        $result = $this->redis->connection()->command('exists', [$this->manualOverrideKey($driverId)]);

        return \is_int($result) && $result > 0;
    }

    private function manualOverrideKey(string $driverId): string
    {
        return "driver:{$driverId}:schedule_override";
    }

    private function bringOnline(string $driverId): void
    {
        $connection = $this->redis->connection();
        $connection->command('sadd', [$this->config->onlineKey(), $driverId]);
        $connection->command('set', [$this->config->stateKey($driverId), 'online']);
    }

    private function sendOffline(string $driverId): void
    {
        $this->redisStore->markOffline($driverId);
    }

    private function isDriverOnline(string $driverId): bool
    {
        $state = $this->redis->connection()->command('get', [$this->config->stateKey($driverId)]);

        return \is_string($state) && ($state === 'online' || $state === DriverAvailabilityStatus::ONLINE->value);
    }

    /**
     * @return Collection<int, DriverSchedule>
     */
    private function findDriversToBringOnline(CarbonImmutable $now): Collection
    {
        $currentDay = $now->dayOfWeekIso % 7;
        $currentTime = $now->format('H:i:s');

        $schedules = DriverSchedule::query()
            ->where('enabled', true)
            ->where('day_of_week', $currentDay)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>', $currentTime)
            ->get();

        return $schedules->filter(fn (DriverSchedule $schedule): bool => ! $this->isDriverOnline((string) $schedule->driver_id));
    }

    /**
     * @return Collection<int, DriverSchedule>
     */
    private function findDriversToSendOffline(CarbonImmutable $now): Collection
    {
        $currentDay = $now->dayOfWeekIso % 7;
        $currentTime = $now->format('H:i:s');

        $graceSeconds = 60;
        $thresholdTime = $now->copy()->subSeconds($graceSeconds)->format('H:i:s');

        $schedules = DriverSchedule::query()
            ->where('enabled', true)
            ->where('day_of_week', $currentDay)
            ->where('end_time', '<=', $currentTime)
            ->where('end_time', '>', $thresholdTime)
            ->get();

        return $schedules->filter(fn (DriverSchedule $schedule): bool => $this->isDriverOnline((string) $schedule->driver_id));
    }

    /**
     * @param Collection<int, DriverSchedule> $schedules
     */
    private function findNextSlot(Collection $schedules, CarbonImmutable $now): ?DriverSchedule
    {
        if ($schedules->isEmpty()) {
            return null;
        }

        $currentDay = $now->dayOfWeekIso % 7;
        $currentTime = $now->format('H:i:s');

        $todayUpcoming = $schedules
            ->filter(fn (DriverSchedule $s): bool => $s->day_of_week === $currentDay && $s->start_time > $currentTime)
            ->sortBy('start_time')
            ->first();

        if ($todayUpcoming !== null) {
            return $todayUpcoming;
        }

        for ($daysAhead = 1; $daysAhead <= 7; $daysAhead++) {
            $futureDay = ($currentDay + $daysAhead) % 7;

            $firstSlot = $schedules
                ->filter(fn (DriverSchedule $s): bool => $s->day_of_week === $futureDay)
                ->sortBy('start_time')
                ->first();

            if ($firstSlot !== null) {
                return $firstSlot;
            }
        }

        return null;
    }
}
