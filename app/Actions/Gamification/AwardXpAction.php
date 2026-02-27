<?php

declare(strict_types=1);

namespace App\Actions\Gamification;

use App\Enums\UserTier;
use App\Events\Gamification\LevelUp;
use App\Models\User;
use App\Models\UserLevel;

final readonly class AwardXpAction
{
    public function handle(User $user, int $points): UserLevel
    {
        $userLevel = UserLevel::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['level' => 1, 'xp' => 0, 'tier' => UserTier::BRONZE],
        );

        $oldTier = $userLevel->tier;
        $leveledUp = $userLevel->addXp($points);
        $userLevel->save();

        if ($leveledUp && $oldTier !== $userLevel->tier) {
            LevelUp::dispatch($user, $oldTier, $userLevel->tier);
        }

        return $userLevel;
    }
}
