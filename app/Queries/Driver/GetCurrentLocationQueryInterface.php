<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverRealtimeLocationData;

interface GetCurrentLocationQueryInterface
{
    public function execute(string $driverId): ?DriverRealtimeLocationData;
}
