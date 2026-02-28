<?php

declare(strict_types=1);

namespace App\Listeners\Streak;

use App\Events\Streak\StreakLevelUp;
use App\Notifications\Streak\StreakLevelUpNotification;

final readonly class SendStreakLevelUpNotification
{
    public function handle(StreakLevelUp $event): void
    {
        $event->user->notify(new StreakLevelUpNotification(
            currentStreak: $event->currentStreak,
            newLevel: $event->newLevel,
            discountPercent: $event->newLevel->discountPercent(),
        ));
    }
}
