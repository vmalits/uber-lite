<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Enums\RideStatus;
use App\Models\Ride;
use Illuminate\Validation\ValidationException;

final class CancelRide
{
    public function handle(Ride $ride): void
    {
        if (! $ride->status->canTransitionTo(RideStatus::CANCELLED)) {
            throw ValidationException::withMessages([
                'ride' => ['Ride cannot be cancelled in its current status.'],
            ]);
        }

        $ride->update([
            'status' => RideStatus::CANCELLED,
        ]);
    }
}
