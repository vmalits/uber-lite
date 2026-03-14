<?php

declare(strict_types=1);

namespace App\Data\Ride;

use Spatie\LaravelData\Data;

final class CreateRideMessageData extends Data
{
    public function __construct(
        public string $message,
    ) {}
}
