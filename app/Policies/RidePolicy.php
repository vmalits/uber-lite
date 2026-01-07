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

    public function accept(User $user, Ride $ride): bool
    {
        return $user->isDriver()
            && $ride->status === RideStatus::PENDING
            && $ride->driver_id === null;
    }

    public function view(User $user, Ride $ride): bool
    {
        return $user->id === $ride->rider_id;
    }

    public function cancel(User $user, Ride $ride): bool
    {
        return $user->id === $ride->rider_id
            || $user->id === $ride->driver_id;
    }

    public function onTheWay(User $user, Ride $ride): bool
    {
        return $user->id === $ride->driver_id
            && $ride->status === RideStatus::ACCEPTED;
    }

    public function arrived(User $user, Ride $ride): bool
    {
        return $user->id === $ride->driver_id
            && $ride->status === RideStatus::ON_THE_WAY;
    }

    public function start(User $user, Ride $ride): bool
    {
        return $user->id === $ride->driver_id
            && $ride->status === RideStatus::ARRIVED;
    }

    public function complete(User $user, Ride $ride): bool
    {
        return $user->id === $ride->driver_id;
    }

    public function rate(User $user, Ride $ride): bool
    {
        return $user->id === $ride->rider_id && $ride->status === RideStatus::COMPLETED;
    }
}
