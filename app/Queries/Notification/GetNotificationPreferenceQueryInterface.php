<?php

declare(strict_types=1);

namespace App\Queries\Notification;

use App\Models\NotificationPreference;

interface GetNotificationPreferenceQueryInterface
{
    public function execute(string $userId): NotificationPreference;
}
