<?php

declare(strict_types=1);

namespace App\Actions\Notification;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

final readonly class MarkAllNotificationsAsReadAction
{
    public function handle(User $user): int
    {
        return DatabaseNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
