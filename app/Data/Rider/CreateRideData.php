<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class CreateRideData extends Data
{
    public function __construct(
        public string $origin_address,
        public float $origin_lat,
        public float $origin_lng,
        public string $destination_address,
        public float $destination_lat,
        public float $destination_lng,
    ) {}
}
