<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\Ride;
use App\Models\RideStop;

final readonly class DeleteRideStopAction
{
    public function handle(Ride $ride, RideStop $stop): void
    {
        $stop->delete();

        $ride->stops()
            ->where('order', '>', $stop->order)
            ->decrement('order');
    }
}
