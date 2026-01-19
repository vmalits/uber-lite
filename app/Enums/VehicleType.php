<?php

declare(strict_types=1);

namespace App\Enums;

enum VehicleType: string
{
    case SEDAN = 'sedan';
    case SUV = 'suv';
    case MINIVAN = 'minivan';
    case HATCHBACK = 'hatchback';
    case COUPE = 'coupe';
    case PICKUP = 'pickup';
}
