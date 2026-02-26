<?php

declare(strict_types=1);

namespace App\Enums;

enum AchievementCategory: string
{
    case DRIVER = 'driver';
    case RIDER = 'rider';
    case COMMON = 'common';
}
