<?php

declare(strict_types=1);

namespace App\Actions\Ride;

use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\RideTip;
use Illuminate\Validation\ValidationException;

final readonly class CreateTipForRideAction
{
    public function handle(Ride $ride, int $amount, ?string $comment = null): RideTip
    {
        if ($ride->status !== RideStatus::COMPLETED) {
            throw ValidationException::withMessages([
                'ride' => 'Cannot add tip to incomplete ride.',
            ]);
        }

        if ($ride->tip()->exists()) {
            throw ValidationException::withMessages([
                'tip' => 'Tip already exists for this ride.',
            ]);
        }

        return RideTip::create([
            'ride_id'   => $ride->id,
            'rider_id'  => $ride->rider_id,
            'driver_id' => $ride->driver_id,
            'amount'    => $amount,
            'comment'   => $comment,
        ]);
    }
}
