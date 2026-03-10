<?php

declare(strict_types=1);

namespace App\Data\Rider;

final readonly class AddRideStopData
{
    public function __construct(
        public string $address,
        public ?float $lat = null,
        public ?float $lng = null,
    ) {}
}
