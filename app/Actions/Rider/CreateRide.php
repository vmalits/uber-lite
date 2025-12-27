<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\CreateRideData;
use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class CreateRide
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private RideStateMachine $rideStateMachine,
    ) {}

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function handle(User $user, CreateRideData $data): Ride
    {
        return $this->databaseManager->transaction(
            callback: function () use ($user, $data): Ride {
                $hasActiveRide = Ride::query()
                    ->where('rider_id', $user->id)
                    ->active()
                    ->lockForUpdate()
                    ->exists();

                if ($hasActiveRide) {
                    throw ValidationException::withMessages([
                        'ride' => ['You already have an active ride.'],
                    ]);
                }

                /** @var Ride $ride */
                $ride = Ride::query()->create([
                    'rider_id'            => $user->id,
                    'origin_address'      => $data->origin_address,
                    'origin_lat'          => $data->origin_lat,
                    'origin_lng'          => $data->origin_lng,
                    'destination_address' => $data->destination_address,
                    'destination_lat'     => $data->destination_lat,
                    'destination_lng'     => $data->destination_lng,
                    'status'              => RideStatus::PENDING,
                ]);

                $this->rideStateMachine->transition(
                    ride: $ride,
                    to: RideStatus::PENDING,
                    actorType: ActorType::RIDER,
                    actorId: $user->id,
                );

                return $ride->refresh();
            },
            attempts: 3,
        );
    }
}
