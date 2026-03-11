<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\DriverPayout;
use App\Models\User;

final class DriverPayoutPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function view(User $user, DriverPayout $payout): bool
    {
        return $payout->driver()->is($user);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function viewBalance(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function viewAnyAdmin(User $user): bool
    {
        return $user->isAdmin();
    }

    public function viewAdmin(User $user, DriverPayout $payout): bool
    {
        return $user->isAdmin();
    }

    public function updateStatus(User $user, DriverPayout $payout): bool
    {
        return $user->isAdmin();
    }
}
