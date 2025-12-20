<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

final class RidePolicy
{
    public function create(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function view(User $user, Ride $ride): bool
    {
        return $user->id === $ride->rider_id;
    }

    public function cancel(User $user, Ride $ride): bool
    {
        return $user->id === $ride->rider_id;
    }
}
