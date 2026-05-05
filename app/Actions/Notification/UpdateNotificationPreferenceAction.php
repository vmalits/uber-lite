<?php

declare(strict_types=1);

namespace App\Actions\Notification;

use App\Models\NotificationPreference;

final readonly class UpdateNotificationPreferenceAction
{
    /**
     * @param array<string, bool> $data
     */
    public function handle(NotificationPreference $preference, array $data): NotificationPreference
    {
        $preference->update($data);

        return $preference->refresh();
    }
}
