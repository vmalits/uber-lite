<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DeviceToken;
use App\Models\User;

final class DeviceTokenPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, DeviceToken $deviceToken): bool
    {
        return $user->id === $deviceToken->user_id;
    }

    public function update(User $user, DeviceToken $deviceToken): bool
    {
        return $user->id === $deviceToken->user_id;
    }
}
