<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

final class GetActiveRideQuery implements GetActiveRideQueryInterface
{
    public function execute(User $user): ?Ride
    {
        return QueryBuilder::for(
            Ride::query()
                ->where('driver_id', $user->id)
                ->whereIn('status', [
                    RideStatus::ACCEPTED,
                    RideStatus::ON_THE_WAY,
                    RideStatus::ARRIVED,
                    RideStatus::STARTED,
                ]),
        )
            ->allowedIncludes(['rider'])
            ->first();
    }
}
