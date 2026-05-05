<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\RideTimelineData;
use App\Models\Ride;

interface GetRideTimelineQueryInterface
{
    public function execute(Ride $ride): RideTimelineData;
}
