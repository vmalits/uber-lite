<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;

final class RideMessagePolicy
{
    public function viewAny(User $user, Ride $ride): bool
    {
        return $this->isParticipant($user, $ride);
    }

    public function create(User $user, Ride $ride): bool
    {
        return $this->isParticipant($user, $ride)
            && $this->isActiveRide($ride);
    }

    public function markAsRead(User $user, Ride $ride): bool
    {
        return $this->isParticipant($user, $ride);
    }

    private function isParticipant(User $user, Ride $ride): bool
    {
        return $ride->rider()->is($user)
            || ($ride->driver()->exists() && $ride->driver()->is($user));
    }

    private function isActiveRide(Ride $ride): bool
    {
        return \in_array(
            $ride->status,
            [
                RideStatus::ACCEPTED,
                RideStatus::ON_THE_WAY,
                RideStatus::ARRIVED,
                RideStatus::STARTED,
            ],
            true,
        );
    }
}
