<?php

declare(strict_types=1);

namespace App\Enums;

enum FavoriteRouteType: string
{
    case HOME = 'home';
    case WORK = 'work';
    case SCHOOL = 'school';
    case GYM = 'gym';
    case OTHER = 'other';
}
