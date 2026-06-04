<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\BlockedDriver;
use App\Models\User;

final class BlockedDriverPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function view(User $user, BlockedDriver $blockedDriver): bool
    {
        return $blockedDriver->rider_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function delete(User $user, BlockedDriver $blockedDriver): bool
    {
        return $blockedDriver->rider_id === $user->id;
    }
}
