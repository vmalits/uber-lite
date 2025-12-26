<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Ride;
use DB;

class RideObserver
{
    public function creating(Ride $ride): void
    {
        // Если координаты заданы — сразу формируем geography point
        if ($ride->origin_lat !== null && $ride->origin_lng !== null) {
            $ride->origin_point = DB::raw(\sprintf(
                'ST_SetSRID(ST_MakePoint(%F, %F), 4326)',
                $ride->origin_lng,
                $ride->origin_lat,
            ));
        }

        if ($ride->destination_lat !== null && $ride->destination_lng !== null) {
            $ride->destination_point = DB::raw(\sprintf(
                'ST_SetSRID(ST_MakePoint(%F, %F), 4326)',
                $ride->destination_lng,
                $ride->destination_lat,
            ));
        }
    }
}
