<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\FavoriteLocation;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetFavoriteLocationsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, FavoriteLocation>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator;
}
