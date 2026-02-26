<?php

declare(strict_types=1);

namespace App\Queries\Gamification;

use App\Data\Gamification\AchievementData;
use App\Enums\AchievementCategory;
use App\Models\Achievement;
use Illuminate\Support\Collection;

final readonly class GetAvailableAchievementsQuery
{
    /**
     * @return Collection<int, AchievementData>
     */
    public function execute(AchievementCategory $category): Collection
    {
        $query = match ($category) {
            AchievementCategory::DRIVER => Achievement::query()->where('is_active', true)->forDrivers(),
            AchievementCategory::RIDER  => Achievement::query()->where('is_active', true)->forRiders(),
            default                     => Achievement::query()->where('is_active', true),
        };

        return $query->get()
            ->map(fn (Achievement $achievement): AchievementData => new AchievementData(
                id: $achievement->id,
                name: $achievement->name,
                key: $achievement->key,
                description: $achievement->description,
                icon: $achievement->icon,
                category: $achievement->category->value,
                target_value: $achievement->target_value,
                points_reward: $achievement->points_reward,
            ));
    }
}
