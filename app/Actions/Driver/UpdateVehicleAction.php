<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Vehicle\CreateVehicleData;
use App\Models\Vehicle;

final readonly class UpdateVehicleAction
{
    public function handle(Vehicle $vehicle, CreateVehicleData $data): Vehicle
    {
        $vehicle->update([
            'brand'        => $data->brand,
            'model'        => $data->model,
            'year'         => $data->year,
            'color'        => $data->color,
            'plate_number' => $data->plate_number,
            'vehicle_type' => $data->vehicle_type,
            'seats'        => $data->seats,
        ]);

        return $vehicle;
    }
}
