<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class CreateFavoriteLocationData extends Data
{
    public function __construct(
        public string $name,
        public float $lat,
        public float $lng,
        public string $address,
    ) {}
}
