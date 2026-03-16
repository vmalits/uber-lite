<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class UpdateFavoriteLocationData extends Data
{
    public function __construct(
        public ?string $name = null,
        public ?float $lat = null,
        public ?float $lng = null,
        public ?string $address = null,
    ) {}
}
