<?php

declare(strict_types=1);

namespace App\Enums;

enum TimePeriod: string
{
    case CURRENT_MONTH = 'current_month';
    case LAST_MONTH = 'last_month';
    case ALL_TIME = 'all_time';

    public function label(): string
    {
        return match ($this) {
            self::CURRENT_MONTH => 'Current Month',
            self::LAST_MONTH    => 'Last Month',
            self::ALL_TIME      => 'All Time',
        };
    }
}
