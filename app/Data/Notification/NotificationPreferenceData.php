<?php

declare(strict_types=1);

namespace App\Data\Notification;

use App\Models\NotificationPreference;
use Spatie\LaravelData\Data;

final class NotificationPreferenceData extends Data
{
    public function __construct(
        public bool $ride_updates,
        public bool $promo,
        public bool $ride_split,
        public bool $achievement,
        public bool $streak,
        public bool $safety,
        public bool $push_enabled,
        public bool $email_enabled,
    ) {}

    public static function fromModel(NotificationPreference $preference): self
    {
        return new self(
            ride_updates: $preference->ride_updates,
            promo: $preference->promo,
            ride_split: $preference->ride_split,
            achievement: $preference->achievement,
            streak: $preference->streak,
            safety: $preference->safety,
            push_enabled: $preference->push_enabled,
            email_enabled: $preference->email_enabled,
        );
    }

    public static function default(): self
    {
        return new self(
            ride_updates: true,
            promo: true,
            ride_split: true,
            achievement: true,
            streak: true,
            safety: true,
            push_enabled: true,
            email_enabled: true,
        );
    }
}
