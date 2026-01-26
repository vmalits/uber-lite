<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverStatsData;
use App\Models\User;

interface GetDriverStatsQueryInterface
{
    public function execute(User $user): DriverStatsData;
}
