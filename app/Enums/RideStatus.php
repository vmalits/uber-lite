<?php

declare(strict_types=1);

namespace App\Enums;

enum RideStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case ON_THE_WAY = 'on_the_way';
    case ARRIVED = 'arrived';
    case STARTED = 'started';

    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function isActive(): bool
    {
        return \in_array($this, [
            self::PENDING,
            self::ACCEPTED,
            self::ON_THE_WAY,
            self::ARRIVED,
            self::STARTED,
        ], true);
    }

    public function isFinal(): bool
    {
        return \in_array($this, [
            self::COMPLETED,
            self::CANCELLED,
        ], true);
    }
}
