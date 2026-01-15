<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Enums\DriverAvailabilityStatus;
use App\Models\DriverLocation;
use App\Models\User;

readonly class GoOnlineAction
{
    public function __construct(
        private DriverLocation $location,
    ) {}

    public function handle(User $driver, float $latitude, float $longitude): DriverLocation
    {
        return $this->location->updateOrCreate(
            ['driver_id' => $driver->id],
            [
                'status'         => DriverAvailabilityStatus::ONLINE,
                'lat'            => $latitude,
                'lng'            => $longitude,
                'last_active_at' => now(),
            ],
        );
    }
}
