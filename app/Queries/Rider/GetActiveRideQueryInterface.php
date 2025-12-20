<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\Ride;
use App\Models\User;

interface GetActiveRideQueryInterface
{
    public function execute(User $user): ?Ride;
}
