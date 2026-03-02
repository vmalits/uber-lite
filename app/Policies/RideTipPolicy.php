<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\RideTip;
use App\Models\User;

final class RideTipPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function view(User $user, RideTip $tip): bool
    {
        return $tip->driver()->is($user);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }
}
