<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\Ride;

interface GetRideQueryInterface
{
    public function execute(Ride $ride): Ride;
}
