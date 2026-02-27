<?php

declare(strict_types=1);

namespace App\Data\Safety;

use Spatie\LaravelData\Data;

final class SosData extends Data
{
    public function __construct(
        public float $latitude,
        public float $longitude,
        public ?string $message = null,
    ) {}
}
