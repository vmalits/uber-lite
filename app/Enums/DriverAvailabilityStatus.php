<?php

declare(strict_types=1);

namespace App\Enums;

enum DriverAvailabilityStatus: string
{
    case OFFLINE = 'offline';
    case ONLINE = 'online';
    case BUSY = 'busy';
}
