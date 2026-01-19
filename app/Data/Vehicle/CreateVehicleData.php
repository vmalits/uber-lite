<?php

declare(strict_types=1);

namespace App\Data\Vehicle;

use App\Enums\VehicleType;
use Spatie\LaravelData\Data;

final class CreateVehicleData extends Data
{
    public function __construct(
        public string $brand,
        public string $model,
        public int $year,
        public string $color,
        public string $plate_number,
        public VehicleType $vehicle_type,
        public int $seats,
    ) {}
}
