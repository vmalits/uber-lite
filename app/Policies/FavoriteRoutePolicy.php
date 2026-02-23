<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\FavoriteRoute;
use App\Models\User;

final class FavoriteRoutePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FavoriteRoute $favoriteRoute): bool
    {
        return $user->id === $favoriteRoute->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, FavoriteRoute $favoriteRoute): bool
    {
        return $user->id === $favoriteRoute->user_id;
    }

    public function delete(User $user, FavoriteRoute $favoriteRoute): bool
    {
        return $user->id === $favoriteRoute->user_id;
    }
}
