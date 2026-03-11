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
        return $user->isAdmin()
            || $user->role === UserRole::RIDER
            || ($user->role === UserRole::DRIVER);
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

    public function share(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user);
    }

    public function split(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user);
    }

    public function updateNote(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            && \in_array($ride->status, [RideStatus::SCHEDULED, RideStatus::PENDING], true);
    }

    public function updateScheduled(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            && $ride->status === RideStatus::SCHEDULED;
    }

    public function applyPromoCode(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            && \in_array($ride->status, [RideStatus::SCHEDULED, RideStatus::PENDING], true);
    }

    public function verifyPin(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            && $ride->driver()->exists()
            && \in_array(
                $ride->status, [
                    RideStatus::ACCEPTED,
                    RideStatus::ON_THE_WAY,
                    RideStatus::ARRIVED,
                ],
                true);
    }

    public function addStop(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            && \in_array(
                $ride->status,
                [RideStatus::PENDING, RideStatus::ACCEPTED, RideStatus::ON_THE_WAY],
                true,
            );
    }

    public function viewReceipt(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            && $ride->status === RideStatus::COMPLETED;
    }

    public function addTip(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            && $ride->status === RideStatus::COMPLETED;
    }
}
