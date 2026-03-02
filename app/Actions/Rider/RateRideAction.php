<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Actions\Ride\CreateTipForRideAction;
use App\Data\Rider\RateRideData;
use App\Models\Ride;
use App\Models\RideRating;
use App\Models\User;
use Illuminate\Validation\ValidationException;

final readonly class RateRideAction
{
    public function __construct(
        private CreateTipForRideAction $createTipForRideAction,
    ) {}

    public function handle(Ride $ride, RateRideData $data, User $rider): Ride
    {
        $ride->loadMissing('rating');

        $existing = $ride->rating;

        if ($existing) {
            if (! $existing->canUpdateRating()) {
                throw ValidationException::withMessages([
                    'rating' => ['Rating can be updated only after 24 hours.'],
                ]);
            }

            $existing->update([
                'rating'  => $data->rating,
                'comment' => $data->comment,
            ]);
        } else {
            RideRating::query()->create([
                'ride_id'  => $ride->id,
                'rider_id' => $rider->id,
                'rating'   => $data->rating,
                'comment'  => $data->comment,
            ]);
        }

        if ($data->tip !== null) {
            $this->createTipForRideAction->handle(
                ride: $ride,
                amount: $data->tip->amount,
                comment: $data->tip->comment,
            );
        }

        return $ride->refresh()->load(['rating', 'tip']);
    }
}
