<?php

declare(strict_types=1);

namespace App\Queries\Gamification;

use App\Data\Gamification\RiderLeaderboardData;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserRideStreak;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetRiderLeaderboardQuery
{
    /**
     * @return Collection<int, RiderLeaderboardData>
     */
    public function execute(int $limit = 10): Collection
    {
        $query = User::query()
            ->where('role', UserRole::RIDER)
            ->whereNull('banned_at')
            ->withCount([
                'riderRides as total_rides' => fn (Builder $query) => $query->where(
                    'status', RideStatus::COMPLETED,
                ),
            ])
            ->addSelect([
                'current_streak' => UserRideStreak::query()
                    ->select('current_streak')
                    ->whereColumn('user_id', 'users.id')
                    ->limit(1),
            ]);

        /** @var QueryBuilder<User> $qb */
        $qb = QueryBuilder::for($query)
            ->allowedSorts([
                AllowedSort::field('current_streak'),
                AllowedSort::field('total_rides'),
            ])
            ->defaultSort('-current_streak');

        $riders = $qb->limit($limit)->get();

        $rank = 1;

        return $riders->map(function (User $rider) use (&$rank): RiderLeaderboardData {
            /** @var mixed $streak */
            $streak = $rider->current_streak;

            return new RiderLeaderboardData(
                id: $rider->id,
                first_name: $rider->first_name ?? '',
                last_name: $rider->last_name ?? '',
                avatar_paths: $rider->avatar_paths,
                currentStreak: is_numeric($streak) ? (int) $streak : 0,
                totalRides: $rider->total_rides ?? 0,
                rank: $rank++,
            );
        });
    }
}
