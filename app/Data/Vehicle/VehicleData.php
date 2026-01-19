<?php

declare(strict_types=1);

namespace App\Data\Vehicle;

use App\Data\DateData;
use App\Enums\VehicleType;
use App\Models\Vehicle;
use Spatie\LaravelData\Data;

final class VehicleData extends Data
{
    public function __construct(
        public string $id,
        public string $brand,
        public string $model,
        public int $year,
        public string $color,
        public string $plate_number,
        public VehicleType $vehicle_type,
        public int $seats,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(Vehicle $vehicle): self
    {
        /** @var VehicleType $vehicleType */
        $vehicleType = $vehicle->vehicle_type;

        return new self(
            id: $vehicle->id,
            brand: $vehicle->brand,
            model: $vehicle->model,
            year: $vehicle->year,
            color: $vehicle->color,
            plate_number: $vehicle->plate_number,
            vehicle_type: $vehicleType,
            seats: $vehicle->seats,
            created_at: DateData::fromCarbon($vehicle->created_at),
            updated_at: DateData::fromCarbon($vehicle->updated_at),
        );
    }
}
