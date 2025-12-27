<?php

declare(strict_types=1);

namespace App\Enums;

enum ActorType: string
{
    case DRIVER = 'driver';

    case RIDER = 'rider';

    case SYSTEM = 'system';

    case ADMIN = 'admin';
}
