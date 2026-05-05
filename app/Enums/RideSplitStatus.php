<?php

declare(strict_types=1);

namespace App\Enums;

enum RideSplitStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
    case CANCELLED = 'cancelled';

    public function canCancel(): bool
    {
        return $this === self::PENDING;
    }
}
