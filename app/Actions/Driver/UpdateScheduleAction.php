<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\ScheduleData;
use App\Data\Driver\UpdateScheduleData;
use App\Models\DriverSchedule;

final readonly class UpdateScheduleAction
{
    public function handle(DriverSchedule $schedule, UpdateScheduleData $data): ScheduleData
    {
        if ($data->dayOfWeek !== null) {
            $schedule->day_of_week = $data->dayOfWeek;
        }

        if ($data->startTime !== null) {
            $schedule->start_time = $data->startTime;
        }

        if ($data->endTime !== null) {
            $schedule->end_time = $data->endTime;
        }

        if ($data->enabled !== null) {
            $schedule->enabled = $data->enabled;
        }

        $schedule->save();

        return ScheduleData::from($schedule);
    }
}
