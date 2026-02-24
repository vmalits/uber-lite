<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\FavoriteRoute;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetFavoriteRoutesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, FavoriteRoute>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator;
}
