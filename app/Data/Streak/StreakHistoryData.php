<?php

declare(strict_types=1);

namespace App\Data\Streak;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class StreakHistoryData extends Data
{
    public function __construct(
        #[MapName('streak_count')]
        public int $streakCount,
        public string $date,
        #[MapName('ride_completed')]
        public bool $rideCompleted,
    ) {}
}
