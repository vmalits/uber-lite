<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\FavoriteLocation;

final class GetFavoriteLocationQuery implements GetFavoriteLocationQueryInterface
{
    public function execute(string $userId, string $favoriteId): ?FavoriteLocation
    {
        /** @var FavoriteLocation|null $favorite */
        $favorite = FavoriteLocation::query()
            ->where('user_id', $userId)
            ->where('id', $favoriteId)
            ->first();

        return $favorite;
    }
}
