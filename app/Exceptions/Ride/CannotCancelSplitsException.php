<?php

declare(strict_types=1);

namespace App\Exceptions\Ride;

use RuntimeException;

final class CannotCancelSplitsException extends RuntimeException
{
    public function __construct(string $message = 'Cannot cancel splits at this time.')
    {
        parent::__construct($message);
    }
}
