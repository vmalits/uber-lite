<?php

declare(strict_types=1);

namespace App\Enums;

enum BanSource: string
{
    case ADMIN = 'admin';
    case SYSTEM = 'system';
    case AUTOMATED = 'automated';
}
