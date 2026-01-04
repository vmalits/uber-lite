<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\DriverBan;
use App\Models\User;

interface GetActiveBanQueryInterface
{
    public function execute(User $driver): ?DriverBan;
}
