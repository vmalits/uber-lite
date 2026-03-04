<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\FavoriteDriver;
use App\Models\User;

final class FavoriteDriverPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function view(User $user, FavoriteDriver $favoriteDriver): bool
    {
        return $favoriteDriver->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function delete(User $user, FavoriteDriver $favoriteDriver): bool
    {
        return $favoriteDriver->user_id === $user->id;
    }
}
