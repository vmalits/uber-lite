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
        $this->setLocationPoint($driverLocation);
    }

    public function updating(DriverLocation $driverLocation): void
    {
        if ($driverLocation->isDirty(['lat', 'lng'])) {
            $this->setLocationPoint($driverLocation);
        }
    }

    private function setLocationPoint(DriverLocation $driverLocation): void
    {
        /** @phpstan-ignore booleanAnd.alwaysTrue, notIdentical.alwaysTrue, notIdentical.alwaysTrue */
        if ($driverLocation->lat !== null && $driverLocation->lng !== null) {
            $driverLocation->location_point = $this->databaseManager->raw(\sprintf(
                'ST_SetSRID(ST_MakePoint(%F, %F), 4326)',
                $driverLocation->lng,
                $driverLocation->lat,
            ));
        }
    }
}
