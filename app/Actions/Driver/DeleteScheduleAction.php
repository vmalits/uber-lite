<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Models\DriverSchedule;

final readonly class DeleteScheduleAction
{
    public function handle(DriverSchedule $schedule): void
    {
        $schedule->delete();
    }
}
