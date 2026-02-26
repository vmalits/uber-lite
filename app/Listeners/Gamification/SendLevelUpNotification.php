<?php

declare(strict_types=1);

namespace App\Listeners\Gamification;

use App\Events\Gamification\LevelUp;
use App\Notifications\Gamification\LevelUpNotification;

final readonly class SendLevelUpNotification
{
    public function handle(LevelUp $event): void
    {
        $level = $event->user->level;

        $event->user->notify(new LevelUpNotification(
            oldTier: $event->oldTier,
            newTier: $event->newTier,
            level: $level->level ?? 1,
            xp: $level->xp ?? 0,
        ));
    }
}
