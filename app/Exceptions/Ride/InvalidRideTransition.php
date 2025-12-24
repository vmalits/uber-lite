<?php

declare(strict_types=1);

namespace App\Exceptions\Ride;

use App\Enums\RideStatus;
use RuntimeException;

final class InvalidRideTransition extends RuntimeException
{
    public function __construct(
        public readonly RideStatus $from,
        public readonly RideStatus $to,
        string $message = 'Invalid ride status transition',
    ) {
        parent::__construct($message);
    }
}
