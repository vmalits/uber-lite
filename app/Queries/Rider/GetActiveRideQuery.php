<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\Ride;
use App\Models\User;

final class GetActiveRideQuery implements GetActiveRideQueryInterface
{
    public function execute(User $user): ?Ride
    {
        return Ride::query()
            ->where('rider_id', $user->id)
            ->latestActive();
    }
}
