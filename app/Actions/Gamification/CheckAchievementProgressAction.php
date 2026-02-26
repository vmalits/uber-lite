<?php

declare(strict_types=1);

namespace App\Actions\Gamification;

use App\Enums\AchievementCategory;
use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;

final readonly class CheckAchievementProgressAction
{
    public function __construct(
        private UnlockAchievementAction $unlockAchievement,
    ) {}

    public function execute(User $user, string $achievementKey, int $incrementBy = 1): ?UserAchievement
    {
        $achievement = Achievement::where('key', $achievementKey)
            ->where('is_active', true)
            ->first();

        if ($achievement === null) {
            return null;
        }

        $userAchievement = UserAchievement::firstOrCreate(
            [
                'user_id'        => $user->id,
                'achievement_id' => $achievement->id,
            ],
            ['progress' => 0],
        );

        if ($userAchievement->isCompleted()) {
            return $userAchievement;
        }

        $userAchievement->progress += $incrementBy;

        if ($userAchievement->progress >= $achievement->target_value) {
            $userAchievement->completed_at = now();
            $userAchievement->save();

            $this->unlockAchievement->execute($user, $achievement);

            return $userAchievement;
        }

        $userAchievement->save();

        return $userAchievement;
    }

    public function checkRides(User $user, AchievementCategory $category): void
    {
        $completedRides = $user->{($category === AchievementCategory::DRIVER ? 'driver' : 'rider').'Rides'}()
            ->where('status', 'completed')
            ->count();

        $this->execute($user, "{$category->value}_first_ride", $completedRides >= 1 ? 1 : 0);
        $this->execute($user, "{$category->value}_10_rides", min($completedRides, 10));
        $this->execute($user, "{$category->value}_50_rides", min($completedRides, 50));
        $this->execute($user, "{$category->value}_100_rides", min($completedRides, 100));
        $this->execute($user, "{$category->value}_500_rides", min($completedRides, 500));
    }

    public function checkRating(User $user, float $averageRating): void
    {
        $completedRides = $user->driverRides()->where('status', 'completed')->count();

        if ($completedRides >= 100 && $averageRating >= 4.9) {
            $this->execute($user, 'driver_five_star', 1);
        }
    }

    public function checkSpending(User $user, int $totalSpent): void
    {
        $this->execute($user, 'rider_big_spender', min($totalSpent, 1000));
    }

    public function checkUniqueDestinations(User $user, int $uniqueCount): void
    {
        $this->execute($user, 'rider_explorer', min($uniqueCount, 10));
    }
}
