<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\AddRideStopData;
use App\Models\Ride;
use App\Models\RideStop;
use Illuminate\Validation\ValidationException;

final readonly class AddRideStopAction
{
    /**
     * @throws ValidationException
     */
    public function handle(Ride $ride, AddRideStopData $data): RideStop
    {
        $maxStops = 3;
        $currentStopsCount = $ride->stops()->count();
        if ($currentStopsCount >= $maxStops) {
            throw ValidationException::withMessages([
                'stop' => [\sprintf('Maximum number of stops (%d) reached.', $maxStops)],
            ]);
        }

        $nextOrder = $currentStopsCount + 1;

        return RideStop::query()->create([
            'ride_id' => $ride->id,
            'order'   => $nextOrder,
            'address' => $data->address,
            'lat'     => $data->lat,
            'lng'     => $data->lng,
        ]);
    }
}
