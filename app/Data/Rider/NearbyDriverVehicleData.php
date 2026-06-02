<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Models\Vehicle;
use Spatie\LaravelData\Data;

final class NearbyDriverVehicleData extends Data
{
    public function __construct(
        public readonly string $brand,
        public readonly string $model,
        public readonly int $year,
        public readonly string $color,
        public readonly string $vehicle_type,
    ) {}

    public static function fromModel(Vehicle $vehicle): self
    {
        return new self(
            brand: $vehicle->brand,
            model: $vehicle->model,
            year: $vehicle->year,
            color: $vehicle->color,
            vehicle_type: $vehicle->vehicle_type->value,
        );
    }
}
