<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Enums\RideStatus;
use App\Models\Ride;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class CancelRide
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function handle(Ride $ride): Ride
    {
        return $this->databaseManager->transaction(
            callback: function () use ($ride): Ride {
                /** @var Ride $ride */
                $ride = Ride::query()->lockForUpdate()->findOrFail($ride->id);

                if (! $ride->status->canTransitionTo(RideStatus::CANCELLED)) {
                    throw ValidationException::withMessages([
                        'ride' => ['Ride cannot be cancelled in its current status.'],
                    ]);
                }

                $ride->update([
                    'status' => RideStatus::CANCELLED,
                ]);

                return $ride;
            },
            attempts: 3,
        );
    }
}
