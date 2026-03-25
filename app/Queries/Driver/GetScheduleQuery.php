<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\ScheduleData;
use App\Models\User;
use Illuminate\Support\Collection;

final readonly class GetScheduleQuery implements GetScheduleQueryInterface
{
    public function execute(User $driver): Collection
    {
        return $driver->schedules()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->map(fn ($schedule) => ScheduleData::from($schedule));
    }
}
