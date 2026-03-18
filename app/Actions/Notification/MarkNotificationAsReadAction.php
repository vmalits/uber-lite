<?php

declare(strict_types=1);

namespace App\Actions\Notification;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

final readonly class MarkNotificationAsReadAction
{
    public function handle(User $user, string $notificationId): void
    {
        DatabaseNotification::query()
            ->where('id', $notificationId)
            ->where('user_id', $user->id)
            ->update(['read_at' => now()]);
    }
}
