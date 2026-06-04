<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class BlockedDriverData extends Data
{
    public function __construct(
        public readonly string $driver_id,
    ) {}
}
