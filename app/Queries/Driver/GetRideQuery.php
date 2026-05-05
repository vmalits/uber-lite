<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\Ride;

final class GetRideQuery implements GetRideQueryInterface
{
    public function execute(Ride $ride): Ride
    {
        return $ride->load('rating');
    }
}
