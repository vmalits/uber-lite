<?php

declare(strict_types=1);

namespace App\Data\Streak;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class StreakHistoryResponseData extends Data
{
    /**
     * @param DataCollection<int, StreakHistoryData> $history
     */
    public function __construct(
        #[MapName('current_streak')]
        public int $currentStreak,
        #[MapName('longest_streak')]
        public int $longestStreak,
        /** @var DataCollection<int, StreakHistoryData> */
        public DataCollection $history,
    ) {}
}
