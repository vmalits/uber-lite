<?php

declare(strict_types=1);

namespace App\Data\Streak;

use App\Enums\StreakLevel;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class RideStreakData extends Data
{
    public function __construct(
        #[MapName('current_streak')]
        public int $currentStreak,
        #[MapName('longest_streak')]
        public int $longestStreak,
        public StreakLevel $level,
        #[MapName('discount_percent')]
        public int $discountPercent,
        #[MapName('days_to_next_level')]
        public int $daysToNextLevel,
        #[MapName('last_ride_date')]
        public ?string $lastRideDate,
        #[MapName('is_active')]
        public bool $isActive,
    ) {}

    public static function fromModel(?\App\Models\UserRideStreak $streak): self
    {
        if ($streak === null) {
            return new self(
                currentStreak: 0,
                longestStreak: 0,
                level: StreakLevel::NONE,
                discountPercent: 0,
                daysToNextLevel: 3,
                lastRideDate: null,
                isActive: false,
            );
        }

        $level = StreakLevel::fromStreak($streak->current_streak);
        $nextLevel = self::getNextLevel($level);
        $daysToNext = $nextLevel->minStreakDays() - $streak->current_streak;

        return new self(
            currentStreak: $streak->current_streak,
            longestStreak: $streak->longest_streak,
            level: $level,
            discountPercent: $level->discountPercent(),
            daysToNextLevel: max(0, $daysToNext),
            lastRideDate: $streak->last_ride_date?->toDateString(),
            isActive: $streak->isActive(),
        );
    }

    private static function getNextLevel(StreakLevel $current): StreakLevel
    {
        return match ($current) {
            StreakLevel::NONE     => StreakLevel::BRONZE,
            StreakLevel::BRONZE   => StreakLevel::SILVER,
            StreakLevel::SILVER   => StreakLevel::GOLD,
            StreakLevel::GOLD     => StreakLevel::PLATINUM,
            StreakLevel::PLATINUM => StreakLevel::PLATINUM,
        };
    }
}
