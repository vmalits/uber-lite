<?php

declare(strict_types=1);

namespace App\Enums;

enum DriverBanType: string
{
    case TEMPORARY = 'temporary';
    case PERMANENT = 'permanent';
}
