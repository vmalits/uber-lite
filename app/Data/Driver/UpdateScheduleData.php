<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class UpdateScheduleData extends Data
{
    public function __construct(
        #[MapName('day_of_week')]
        public ?int $dayOfWeek = null,

        #[MapName('start_time')]
        public ?string $startTime = null,

        #[MapName('end_time')]
        public ?string $endTime = null,

        #[MapName('enabled')]
        public ?bool $enabled = null,
    ) {}
}
