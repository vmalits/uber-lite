<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\CreateRideData;
use App\Data\Rider\ScheduleRideData;
use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Jobs\Rider\ActivateScheduledRideJob;
use App\Models\Ride;
use App\Models\User;
use App\Services\Ride\RideEstimationService;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class ScheduleRideAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private RideStateMachine $rideStateMachine,
        private RideEstimationService $estimationService,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(User $user, ScheduleRideData $data): Ride
    {
        return $this->databaseManager->transaction(
            callback: function () use ($user, $data): Ride {
                $estimationData = CreateRideData::from([
                    'origin_address'      => $data->origin_address,
                    'origin_lat'          => $data->origin_lat,
                    'origin_lng'          => $data->origin_lng,
                    'destination_address' => $data->destination_address,
                    'destination_lat'     => $data->destination_lat,
                    'destination_lng'     => $data->destination_lng,
                ]);

                $estimates = $this->estimationService->calculateEstimates($estimationData);

                /** @var Ride $ride */
                $ride = Ride::query()->create([
                    'rider_id'               => $user->id,
                    'origin_address'         => $data->origin_address,
                    'origin_lat'             => $data->origin_lat,
                    'origin_lng'             => $data->origin_lng,
                    'destination_address'    => $data->destination_address,
                    'destination_lat'        => $data->destination_lat,
                    'destination_lng'        => $data->destination_lng,
                    'status'                 => RideStatus::SCHEDULED,
                    'scheduled_at'           => $data->scheduled_at,
                    'estimated_price'        => $estimates->price,
                    'estimated_distance_km'  => $estimates->distance_km,
                    'estimated_duration_min' => $estimates->duration_minutes,
                    'base_fee'               => config('pricing.fixed_rates.base_fee'),
                    'price_per_km'           => config('pricing.fixed_rates.per_km'),
                    'price_per_minute'       => config('pricing.fixed_rates.per_minute'),
                ]);

                $this->rideStateMachine->transition(
                    ride: $ride,
                    to: RideStatus::SCHEDULED,
                    actorType: ActorType::RIDER,
                    actorId: $user->id,
                );

                $activationTime = $data->scheduled_at->subMinutes(10);
                $delay = max(0, (int) now()->diffInSeconds($activationTime, false));

                ActivateScheduledRideJob::dispatch($ride->id)
                    ->delay($delay);

                return $ride->refresh();
            },
            attempts: 3,
        );
    }
}
