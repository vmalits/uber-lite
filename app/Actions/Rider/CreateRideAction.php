<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\CreateRideData;
use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;
use App\Services\Ride\RideEstimationService;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class CreateRideAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private RideStateMachine $rideStateMachine,
        private RideEstimationService $estimationService,
    ) {}

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function handle(User $user, CreateRideData $data): Ride
    {
        return $this->databaseManager->transaction(
            callback: function () use ($user, $data): Ride {

                $estimates = $this->estimationService->calculateEstimates($data);

                /** @var Ride $ride */
                $ride = Ride::query()->create([
                    'rider_id'               => $user->id,
                    'origin_address'         => $data->origin_address,
                    'origin_lat'             => $data->origin_lat,
                    'origin_lng'             => $data->origin_lng,
                    'destination_address'    => $data->destination_address,
                    'destination_lat'        => $data->destination_lat,
                    'destination_lng'        => $data->destination_lng,
                    'status'                 => RideStatus::PENDING,
                    'estimated_price'        => $estimates->price,
                    'estimated_distance_km'  => $estimates->distance_km,
                    'estimated_duration_min' => $estimates->duration_minutes,
                    'base_fee'               => config('pricing.fixed_rates.base_fee'),
                    'price_per_km'           => config('pricing.fixed_rates.per_km'),
                    'price_per_minute'       => config('pricing.fixed_rates.per_minute'),
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
