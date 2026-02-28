<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\Gamification\AchievementUnlocked;
use App\Events\Gamification\LevelUp;
use App\Events\Rider\RideStatusChanged;
use App\Events\Streak\StreakLevelUp;
use App\Listeners\Gamification\CheckDriverAchievements;
use App\Listeners\Gamification\CheckRiderAchievements;
use App\Listeners\Gamification\SendAchievementNotification;
use App\Listeners\Gamification\SendLevelUpNotification;
use App\Listeners\Streak\SendStreakLevelUpNotification;
use App\Listeners\Streak\UpdateRideStreakListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class GamificationServiceProvider extends ServiceProvider
{
    protected $listen = [
        RideStatusChanged::class => [
            CheckDriverAchievements::class,
            CheckRiderAchievements::class,
            UpdateRideStreakListener::class,
        ],
        AchievementUnlocked::class => [
            SendAchievementNotification::class,
        ],
        LevelUp::class => [
            SendLevelUpNotification::class,
        ],
        StreakLevelUp::class => [
            SendStreakLevelUpNotification::class,
        ],
    ];
}
