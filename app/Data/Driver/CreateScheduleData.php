<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class CreateScheduleData extends Data
{
    public function __construct(
        #[MapName('day_of_week')]
        public int $dayOfWeek,

        #[MapName('start_time')]
        public string $startTime,

        #[MapName('end_time')]
        public string $endTime,

        #[MapName('enabled')]
        public bool $enabled = true,
    ) {}
}
