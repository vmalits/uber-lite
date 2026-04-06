<?php

declare(strict_types=1);

namespace App\Services\Driver;

use App\Models\DriverSchedule;

final readonly class ScheduleStatusResult
{
    public function __construct(
        public ?DriverSchedule $activeSlot,
        public ?DriverSchedule $nextSlot,
        public bool $manualOverride,
        public ?string $manualOverrideUntil,
    ) {}
}
