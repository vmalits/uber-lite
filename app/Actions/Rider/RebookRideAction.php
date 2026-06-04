<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\CreateRideData;
use App\Models\Ride;
use App\Models\User;

final readonly class RebookRideAction
{
    public function __construct(
        private CreateRideAction $createRideAction,
    ) {}

    public function handle(User $user, Ride $previousRide): Ride
    {
        $data = new CreateRideData(
            origin_address: $previousRide->origin_address,
            origin_lat: (float) $previousRide->origin_lat,
            origin_lng: (float) $previousRide->origin_lng,
            destination_address: $previousRide->destination_address,
            destination_lat: (float) $previousRide->destination_lat,
            destination_lng: (float) $previousRide->destination_lng,
        );

        return $this->createRideAction->handle($user, $data);
    }
}
