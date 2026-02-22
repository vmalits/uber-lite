<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\FareBreakdownData;
use App\Models\Ride;

interface GetFareBreakdownQueryInterface
{
    public function execute(Ride $ride): FareBreakdownData;
}
