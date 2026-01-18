<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\FavoriteLocation;
use App\Models\User;

final class FavoriteLocationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function view(User $user, FavoriteLocation $favorite): bool
    {
        return $favorite->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function update(User $user, FavoriteLocation $favorite): bool
    {
        return $favorite->user_id === $user->id;
    }

    public function delete(User $user, FavoriteLocation $favorite): bool
    {
        return $favorite->user_id === $user->id;
    }
}
