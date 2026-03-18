<?php

declare(strict_types=1);

namespace App\Queries\Notification;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

final readonly class GetUnreadNotificationsCountQuery implements GetUnreadNotificationsCountQueryInterface
{
    public function execute(User $user): int
    {
        return DatabaseNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }
}
