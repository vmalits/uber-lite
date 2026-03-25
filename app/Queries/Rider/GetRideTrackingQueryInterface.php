<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\TrackRideData;
use App\Models\Ride;

interface GetRideTrackingQueryInterface
{
    public function execute(Ride $ride): TrackRideData;
}
