<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\FavoriteLocation;

interface GetFavoriteLocationQueryInterface
{
    public function execute(string $userId, string $favoriteId): ?FavoriteLocation;
}
