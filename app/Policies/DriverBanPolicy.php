<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\DriverBan;
use App\Models\User;

final class DriverBanPolicy
{
    public function view(User $user, DriverBan $ban): bool
    {
        return $ban->driver_id === $user->id || $user->isAdmin();
    }
}
