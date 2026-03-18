<?php

declare(strict_types=1);

namespace App\Queries\Notification;

use App\Models\User;

interface GetUnreadNotificationsCountQueryInterface
{
    public function execute(User $user): int;
}
