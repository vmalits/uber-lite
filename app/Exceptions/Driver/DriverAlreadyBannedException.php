<?php

declare(strict_types=1);

namespace App\Exceptions\Driver;

use App\Models\User;
use DomainException;

class DriverAlreadyBannedException extends DomainException
{
    public static function forDriver(User $driver): self
    {
        return new self(
            \sprintf('Driver with id [%s] is already banned.', $driver->id),
        );
    }
}
