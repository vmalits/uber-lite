<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

final class RidePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function viewAvailable(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

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

    public function accept(User $user, Ride $ride): bool
    {
        return $user->role === UserRole::DRIVER
            && $ride->driver_id === null;
    }

    public function start(User $user, Ride $ride): bool
    {
        return $user->id === $ride->driver_id;
    }

    public function complete(User $user, Ride $ride): bool
    {
        return $user->id === $ride->driver_id;
    }
}
