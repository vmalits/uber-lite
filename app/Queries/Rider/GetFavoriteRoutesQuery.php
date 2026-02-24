<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\FavoriteRoute;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final class GetFavoriteRoutesQuery implements GetFavoriteRoutesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, FavoriteRoute>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator
    {
        return FavoriteRoute::query()
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
