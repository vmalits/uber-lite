<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\FavoriteDriver;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetFavoriteDriversQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, FavoriteDriver>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator;
}
