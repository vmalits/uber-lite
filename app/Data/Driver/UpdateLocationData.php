<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Data;

final class UpdateLocationData extends Data
{
    public function __construct(
        public readonly float $lat,
        public readonly float $lng,
    ) {}
}
