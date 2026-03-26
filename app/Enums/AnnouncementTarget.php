<?php

declare(strict_types=1);

namespace App\Enums;

enum AnnouncementTarget: string
{
    case ALL = 'all';
    case RIDERS = 'riders';
    case DRIVERS = 'drivers';

    public function label(): string
    {
        return match ($this) {
            self::ALL     => 'All Users',
            self::RIDERS  => 'Riders',
            self::DRIVERS => 'Drivers',
        };
    }
}
