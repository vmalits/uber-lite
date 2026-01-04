<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\DriverBan;
use App\Models\User;

class GetActiveBanQuery implements GetActiveBanQueryInterface
{
    public function execute(User $driver): ?DriverBan
    {
        return DriverBan::query()
            ->where('driver_id', $driver->id)
            ->whereNull('unbanned_at')
            ->first();
    }
}
