<?php

declare(strict_types=1);

namespace App\Enums;

enum StreakLevel: string
{
    case NONE = 'none';
    case BRONZE = 'bronze';
    case SILVER = 'silver';
    case GOLD = 'gold';
    case PLATINUM = 'platinum';

    public function discountPercent(): int
    {
        return match ($this) {
            self::NONE     => 0,
            self::BRONZE   => 5,
            self::SILVER   => 10,
            self::GOLD     => 15,
            self::PLATINUM => 20,
        };
    }

    public function minStreakDays(): int
    {
        return match ($this) {
            self::NONE     => 0,
            self::BRONZE   => 3,
            self::SILVER   => 7,
            self::GOLD     => 14,
            self::PLATINUM => 30,
        };
    }

    public static function fromStreak(int $days): self
    {
        if ($days >= 30) {
            return self::PLATINUM;
        }
        if ($days >= 14) {
            return self::GOLD;
        }
        if ($days >= 7) {
            return self::SILVER;
        }
        if ($days >= 3) {
            return self::BRONZE;
        }

        return self::NONE;
    }

    public function label(): string
    {
        return match ($this) {
            self::NONE     => 'No streak',
            self::BRONZE   => '3-Day Streak',
            self::SILVER   => '7-Day Streak',
            self::GOLD     => '14-Day Streak',
            self::PLATINUM => '30-Day Streak',
        };
    }
}
