<?php

declare(strict_types=1);

namespace App\Enums;

enum ReportStatus: string
{
    case PENDING = 'pending';
    case REVIEWED = 'reviewed';
    case RESOLVED = 'resolved';
    case DISMISSED = 'dismissed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING   => 'Pending',
            self::REVIEWED  => 'Reviewed',
            self::RESOLVED  => 'Resolved',
            self::DISMISSED => 'Dismissed',
        };
    }
}
