<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Enums\DriverAvailabilityStatus;
use App\Models\DriverLocation;
use App\Models\User;

readonly class GoOfflineAction
{
    public function __construct(
        private DriverLocation $location,
    ) {}

    public function handle(User $driver): DriverLocation
    {
        return $this->location->updateOrCreate(
            ['driver_id' => $driver->id],
            [
                'status'         => DriverAvailabilityStatus::OFFLINE,
                'last_active_at' => now(),
            ],
        );
    }
}
