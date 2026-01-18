<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

final class RidePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->role === UserRole::RIDER;
    }

    public function viewAvailable(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function accept(User $user, Ride $ride): bool
    {
        return $user->isDriver()
            && $ride->status === RideStatus::PENDING
            && $ride->driver()->doesntExist();
    }

    public function view(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            || $ride->driver()->is($user);
    }

    public function cancel(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            || $ride->driver()->is($user);
    }

    public function onTheWay(User $user, Ride $ride): bool
    {
        return $ride->driver()->is($user)
            && $ride->status === RideStatus::ACCEPTED;
    }

    public function arrived(User $user, Ride $ride): bool
    {
        return $ride->driver()->is($user)
            && $ride->status === RideStatus::ON_THE_WAY;
    }

    public function start(User $user, Ride $ride): bool
    {
        return $ride->driver()->is($user)
            && $ride->status === RideStatus::ARRIVED;
    }

    public function complete(User $user, Ride $ride): bool
    {
        return $ride->driver()->is($user)
            && $ride->status === RideStatus::STARTED;
    }

    public function rate(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            && $ride->status === RideStatus::COMPLETED;
    }
}
