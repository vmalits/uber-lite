<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\DriverRatingData;
use App\Models\Ride;
use App\Models\RideRating;

final readonly class RateRiderAction
{
    public function handle(Ride $ride, DriverRatingData $data): RideRating
    {
        $ride->loadMissing('rating');

        $existing = $ride->rating;

        if ($existing) {
            $existing->update([
                'driver_rating'  => $data->rating,
                'driver_comment' => $data->comment,
            ]);

            return $existing->refresh();
        }

        return RideRating::query()->create([
            'ride_id'        => $ride->id,
            'rider_id'       => $ride->rider_id,
            'driver_rating'  => $data->rating,
            'driver_comment' => $data->comment,
        ]);
    }
}
