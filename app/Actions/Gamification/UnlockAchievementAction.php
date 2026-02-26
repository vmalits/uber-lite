<?php

declare(strict_types=1);

namespace App\Actions\Gamification;

use App\Events\Gamification\AchievementUnlocked;
use App\Models\Achievement;
use App\Models\User;

final readonly class UnlockAchievementAction
{
    public function __construct(
        private AwardXpAction $awardXp,
    ) {}

    public function execute(User $user, Achievement $achievement): void
    {
        $userLevel = $this->awardXp->execute($user, $achievement->points_reward);

        AchievementUnlocked::dispatch(
            $user,
            $achievement,
            $userLevel->tier->value,
            $userLevel->xp,
        );
    }
}
