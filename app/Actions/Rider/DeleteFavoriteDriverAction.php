<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\FavoriteDriver;
use App\Models\User;

final readonly class DeleteFavoriteDriverAction
{
    public function handle(User $user, FavoriteDriver $favorite): void
    {
        $favorite->delete();
    }
}
