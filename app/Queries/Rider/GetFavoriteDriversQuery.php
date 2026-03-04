<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\FavoriteDriver;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final class GetFavoriteDriversQuery implements GetFavoriteDriversQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, FavoriteDriver>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator
    {
        $query = FavoriteDriver::query()
            ->where('user_id', $user->id)
            ->with('driver')
            ->orderBy('created_at', 'desc');

        /** @var QueryBuilder<FavoriteDriver> $qb */
        $qb = QueryBuilder::for($query);

        return $qb->paginate($perPage);
    }
}
