<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Models\Vehicle;

final readonly class DeleteVehicleAction
{
    public function handle(Vehicle $vehicle): bool
    {
        return (bool) $vehicle->delete();
    }
}
