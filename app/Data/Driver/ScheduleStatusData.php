<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class ScheduleStatusData extends Data
{
    public function __construct(
        #[MapName('has_active_slot')]
        public bool $hasActiveSlot,

        #[MapName('active_slot')]
        public ?ScheduleSlotData $activeSlot,

        #[MapName('next_slot')]
        public ?ScheduleSlotData $nextSlot,

        #[MapName('manual_override')]
        public bool $manualOverride,

        #[MapName('manual_override_until')]
        public ?string $manualOverrideUntil,
    ) {}
}
