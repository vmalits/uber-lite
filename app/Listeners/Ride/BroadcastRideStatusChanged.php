<?php

declare(strict_types=1);

namespace App\Listeners\Ride;

use App\Events\Rider\RideStatusChanged;
use App\Events\Rider\RideStatusChangedWs;

class BroadcastRideStatusChanged
{
    public function handle(RideStatusChanged $event): void
    {
        broadcast(new RideStatusChangedWs(
            ride: $event->ride,
            from: $event->from,
            to: $event->to,
        ));
    }
}
