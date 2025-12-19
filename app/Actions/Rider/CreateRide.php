<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\CreateRideData;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;

final readonly class CreateRide
{
    public function handle(User $user, CreateRideData $data): Ride
    {
        return Ride::query()->create([
            'rider_id'            => $user->id,
            'origin_address'      => $data->origin_address,
            'origin_lat'          => $data->origin_lat,
            'origin_lng'          => $data->origin_lng,
            'destination_address' => $data->destination_address,
            'destination_lat'     => $data->destination_lat,
            'destination_lng'     => $data->destination_lng,
            'status'              => RideStatus::PENDING,
        ]);
    }
}
