<?php

declare(strict_types=1);

namespace App\Actions\Gamification;

final readonly class UpdateUserTierAction
{
    public function handle(int $xp): string
    {
        if ($xp >= 5000) {
            return 'platinum';
        }
        if ($xp >= 2000) {
            return 'gold';
        }
        if ($xp >= 500) {
            return 'silver';
        }

        return 'bronze';
    }
}
