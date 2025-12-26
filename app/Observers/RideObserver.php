<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Ride;
use Illuminate\Database\DatabaseManager;

readonly class RideObserver
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    public function creating(Ride $ride): void
    {
        if ($ride->origin_lat !== null && $ride->origin_lng !== null) {
            $ride->origin_point = $this->databaseManager->raw(\sprintf(
                'ST_SetSRID(ST_MakePoint(%F, %F), 4326)',
                $ride->origin_lng,
                $ride->origin_lat,
            ));
        }

        if ($ride->destination_lat !== null && $ride->destination_lng !== null) {
            $ride->destination_point = $this->databaseManager->raw(\sprintf(
                'ST_SetSRID(ST_MakePoint(%F, %F), 4326)',
                $ride->destination_lng,
                $ride->destination_lat,
            ));
        }
    }
}
