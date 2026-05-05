<?php

declare(strict_types=1);

namespace App\Queries\Notification;

use App\Models\NotificationPreference;

final readonly class GetNotificationPreferenceQuery implements GetNotificationPreferenceQueryInterface
{
    public function execute(string $userId): NotificationPreference
    {
        return NotificationPreference::query()
            ->where('user_id', $userId)
            ->firstOrFail();
    }
}
