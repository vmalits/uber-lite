<?php

declare(strict_types=1);

namespace App\Queries\Gamification;

use App\Data\Gamification\DriverLeaderboardData;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetDriverLeaderboardQuery
{
    /**
     * @return Collection<int, DriverLeaderboardData>
     */
    public function execute(int $limit = 10): Collection
    {
        $query = User::query()
            ->where('role', UserRole::DRIVER)
            ->whereNull('banned_at')
            ->withCount([
                'driverRides as total_rides' => fn (Builder $query) => $query->where(
                    'status', RideStatus::COMPLETED,
                ),
            ])
            ->addSelect([
                'rating' => DB::table('ride_ratings')
                    ->selectRaw('AVG(rating)')
                    ->join('rides', 'rides.id', '=', 'ride_ratings.ride_id')
                    ->whereColumn('rides.driver_id', 'users.id'),
            ]);

        /** @var QueryBuilder<User> $qb */
        $qb = QueryBuilder::for($query)
            ->allowedSorts([
                AllowedSort::field('rating'),
                AllowedSort::field('total_rides'),
            ])
            ->defaultSort('-rating');

        $drivers = $qb->limit($limit)->get();

        $rank = 1;

        return $drivers->map(function (User $driver) use (&$rank): DriverLeaderboardData {
            /** @var mixed $rating */
            $rating = $driver->rating;

            return new DriverLeaderboardData(
                id: $driver->id,
                first_name: $driver->first_name ?? '',
                last_name: $driver->last_name ?? '',
                avatar_paths: $driver->avatar_paths,
                rating: is_numeric($rating) ? (float) $rating : 0.0,
                totalRides: $driver->total_rides ?? 0,
                rank: $rank++,
            );
        });
    }
}
