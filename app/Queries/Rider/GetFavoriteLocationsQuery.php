<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\FavoriteLocation;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final class GetFavoriteLocationsQuery implements GetFavoriteLocationsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, FavoriteLocation>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator
    {
        $query = FavoriteLocation::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');

        /** @var QueryBuilder<FavoriteLocation> $qb */
        $qb = QueryBuilder::for($query);

        return $qb->paginate($perPage);
    }
}
