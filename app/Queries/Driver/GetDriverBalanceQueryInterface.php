<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverBalanceData;
use App\Models\User;

interface GetDriverBalanceQueryInterface
{
    public function execute(User $driver): DriverBalanceData;
}
