<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\FavoriteLocation;
use App\Models\User;

final readonly class DeleteFavoriteLocationAction
{
    public function handle(User $user, FavoriteLocation $favorite): void
    {
        $favorite->delete();
    }
}
