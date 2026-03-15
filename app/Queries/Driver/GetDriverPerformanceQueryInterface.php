<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverPerformanceData;
use App\Models\User;

interface GetDriverPerformanceQueryInterface
{
    public function execute(User $driver, string $period): DriverPerformanceData;
}
