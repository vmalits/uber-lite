<?php

declare(strict_types=1);

namespace App\Exceptions\Driver;

use App\Models\User;
use RuntimeException;

final class DriverNotBannedException extends RuntimeException
{
    public static function forDriver(User $driver): self
    {
        return new self('Driver with id ['.$driver->id.'] is not banned.');
    }
}
