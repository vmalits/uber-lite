<?php

declare(strict_types=1);

namespace App\Enums;

enum UserTier: string
{
    case BRONZE = 'bronze';
    case SILVER = 'silver';
    case GOLD = 'gold';
    case PLATINUM = 'platinum';

    public function threshold(): int
    {
        return match ($this) {
            self::BRONZE   => 0,
            self::SILVER   => 500,
            self::GOLD     => 2000,
            self::PLATINUM => 5000,
        };
    }

    public function nextTier(): ?self
    {
        return match ($this) {
            self::BRONZE   => self::SILVER,
            self::SILVER   => self::GOLD,
            self::GOLD     => self::PLATINUM,
            self::PLATINUM => null,
        };
    }

    public static function fromXp(int $xp): self
    {
        if ($xp >= 5000) {
            return self::PLATINUM;
        }
        if ($xp >= 2000) {
            return self::GOLD;
        }
        if ($xp >= 500) {
            return self::SILVER;
        }

        return self::BRONZE;
    }

    public function xpToNextLevel(int $currentXp): ?int
    {
        $nextTier = $this->nextTier();

        if ($nextTier === null) {
            return null;
        }

        return $nextTier->threshold() - $currentXp;
    }
}
