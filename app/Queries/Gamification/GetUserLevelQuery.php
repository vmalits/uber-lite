<?php

declare(strict_types=1);

namespace App\Queries\Gamification;

use App\Data\Gamification\UserLevelData;
use App\Enums\UserTier;
use App\Models\User;
use App\Models\UserLevel;

final readonly class GetUserLevelQuery
{
    public function execute(User $user): UserLevelData
    {
        $userLevel = UserLevel::where('user_id', $user->id)->first();

        if ($userLevel === null) {
            return new UserLevelData(
                level: 1,
                xp: 0,
                tier: UserTier::BRONZE->value,
                xpToNextTier: UserTier::BRONZE->xpToNextLevel(0),
                nextTier: UserTier::BRONZE->nextTier()?->value,
                tierThreshold: UserTier::BRONZE->threshold(),
            );
        }

        return new UserLevelData(
            level: $userLevel->level,
            xp: $userLevel->xp,
            tier: $userLevel->tier->value,
            xpToNextTier: $userLevel->xpToNextLevel(),
            nextTier: $userLevel->nextTier()?->value,
            tierThreshold: $userLevel->tier->threshold(),
        );
    }
}
