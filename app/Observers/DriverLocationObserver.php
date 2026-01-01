<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\DriverLocation;
use Illuminate\Database\DatabaseManager;

readonly class DriverLocationObserver
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    public function creating(DriverLocation $driverLocation): void
    {
        $driverLocation->location_point = $this->databaseManager->raw(\sprintf(
            'ST_SetSRID(ST_MakePoint(%F, %F), 4326)',
            $driverLocation->lng,
            $driverLocation->lat,
        ));
    }
}
