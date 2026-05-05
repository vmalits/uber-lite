<?php

declare(strict_types=1);

namespace App\Queries\Ride;

use App\Models\Ride;
use App\Models\User;

interface GetUnreadMessagesCountQueryInterface
{
    public function execute(Ride $ride, User $user): int;
}
