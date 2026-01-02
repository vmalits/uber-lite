<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\UpdateLocationData;
use App\Models\DriverLocation;
use App\Models\User;

final readonly class UpdateLocation
{
    public function handle(User $driver, UpdateLocationData $data): DriverLocation
    {
        return DriverLocation::updateOrCreate(
            ['driver_id' => $driver->id],
            ['lat' => $data->lat, 'lng' => $data->lng],
        );
    }
}
