<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class NearbyDriverData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly int $distance_meters,
        public readonly int $estimated_arrival_seconds,
        public readonly ?NearbyDriverVehicleData $vehicle,
        public readonly ?float $driver_rating,
    ) {}
}
