<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Vehicle\CreateVehicleData;
use App\Models\User;
use App\Models\Vehicle;

final readonly class CreateVehicleAction
{
    public function handle(User $driver, CreateVehicleData $data): Vehicle
    {
        return Vehicle::query()->create([
            'driver_id'    => $driver->id,
            'brand'        => $data->brand,
            'model'        => $data->model,
            'year'         => $data->year,
            'color'        => $data->color,
            'plate_number' => $data->plate_number,
            'vehicle_type' => $data->vehicle_type,
            'seats'        => $data->seats,
        ]);
    }
}
