<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Achievement;
use App\Models\User;

final class AchievementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Achievement $achievement): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Achievement $achievement): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Achievement $achievement): bool
    {
        return $user->isAdmin();
    }
}
