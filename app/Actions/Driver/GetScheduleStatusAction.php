<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\ScheduleSlotData;
use App\Data\Driver\ScheduleStatusData;
use App\Models\User;
use App\Services\Driver\DriverScheduleSyncService;

final readonly class GetScheduleStatusAction
{
    public function __construct(
        private DriverScheduleSyncService $syncService,
    ) {}

    public function handle(User $driver): ScheduleStatusData
    {
        $result = $this->syncService->getScheduleStatus($driver);

        $activeSlot = $result->activeSlot !== null
            ? new ScheduleSlotData(
                id: $result->activeSlot->id,
                dayOfWeek: $result->activeSlot->day_of_week,
                startTime: $result->activeSlot->start_time,
                endTime: $result->activeSlot->end_time,
            )
            : null;

        $nextSlot = $result->nextSlot !== null
            ? new ScheduleSlotData(
                id: $result->nextSlot->id,
                dayOfWeek: $result->nextSlot->day_of_week,
                startTime: $result->nextSlot->start_time,
                endTime: $result->nextSlot->end_time,
            )
            : null;

        return new ScheduleStatusData(
            hasActiveSlot: $result->activeSlot !== null,
            activeSlot: $activeSlot,
            nextSlot: $nextSlot,
            manualOverride: $result->manualOverride,
            manualOverrideUntil: $result->manualOverrideUntil,
        );
    }
}
