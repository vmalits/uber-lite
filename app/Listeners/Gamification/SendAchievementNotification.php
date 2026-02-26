<?php

declare(strict_types=1);

namespace App\Listeners\Gamification;

use App\Events\Gamification\AchievementUnlocked;
use App\Notifications\Gamification\AchievementUnlockedNotification;

final readonly class SendAchievementNotification
{
    public function handle(AchievementUnlocked $event): void
    {
        $event->user->notify(new AchievementUnlockedNotification(
            achievement: $event->achievement,
            tier: $event->tier,
            totalXp: $event->totalXp,
        ));
    }
}
