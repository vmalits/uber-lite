<?php

declare(strict_types=1);

namespace App\Queries\Gamification;

use App\Data\Gamification\AchievementData;
use App\Data\Gamification\UserAchievementData;
use App\Models\User;
use Illuminate\Support\Collection;

final readonly class GetUserAchievementsQuery
{
    /**
     * @return array{data: Collection<int, UserAchievementData>, meta: array<string, mixed>}
     */
    public function execute(User $user, ?string $status = null, int $perPage = 15): array
    {
        $query = $user->userAchievements()
            ->with('achievement')
            ->orderByDesc('completed_at')
            ->orderByDesc('updated_at');

        if ($status === 'completed') {
            $query->whereNotNull('completed_at');
        } elseif ($status === 'in_progress') {
            $query->whereNull('completed_at');
        }

        $paginator = $query->paginate($perPage);

        $items = $paginator->getCollection()->map(fn ($userAchievement): UserAchievementData => new UserAchievementData(
            id: $userAchievement->id,
            achievement: new AchievementData(
                id: $userAchievement->achievement->id,
                name: $userAchievement->achievement->name,
                key: $userAchievement->achievement->key,
                description: $userAchievement->achievement->description,
                icon: $userAchievement->achievement->icon,
                category: $userAchievement->achievement->category->value,
                target_value: $userAchievement->achievement->target_value,
                points_reward: $userAchievement->achievement->points_reward,
            ),
            progress: $userAchievement->progress,
            progressPercentage: $userAchievement->progressPercentage(),
            completedAt: $userAchievement->completed_at?->toISOString(),
            isCompleted: $userAchievement->isCompleted(),
        ));

        return [
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'last_page'    => $paginator->lastPage(),
            ],
        ];
    }
}
