<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\RiderStatsData;
use App\Models\User;

interface GetRideStatsQueryInterface
{
    public function execute(User $user): RiderStatsData;
}
