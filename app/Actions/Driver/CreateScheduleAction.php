<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\CreateScheduleData;
use App\Data\Driver\ScheduleData;
use App\Models\DriverSchedule;
use App\Models\User;

final readonly class CreateScheduleAction
{
    public function handle(User $driver, CreateScheduleData $data): ScheduleData
    {
        $schedule = DriverSchedule::query()->create([
            'driver_id'   => $driver->id,
            'day_of_week' => $data->dayOfWeek,
            'start_time'  => $data->startTime,
            'end_time'    => $data->endTime,
            'enabled'     => $data->enabled,
        ]);

        return ScheduleData::from($schedule);
    }
}
