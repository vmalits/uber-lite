<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\CreateRideData;
use App\Data\Rider\UpdateScheduledRideData;
use App\Jobs\Rider\ActivateScheduledRideJob;
use App\Models\Ride;
use App\Services\Ride\RideEstimationService;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class UpdateScheduledRideAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private RideEstimationService $estimationService,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Ride $ride, UpdateScheduledRideData $data): Ride
    {
        return $this->databaseManager->transaction(
            callback: function () use ($ride, $data): Ride {
                $updateData = [];

                $hasLocationChange = $data->origin_address !== null
                    || $data->destination_address !== null;

                if ($data->origin_address !== null) {
                    $updateData['origin_address'] = $data->origin_address;
                    $updateData['origin_lat'] = $data->origin_lat;
                    $updateData['origin_lng'] = $data->origin_lng;
                }

                if ($data->destination_address !== null) {
                    $updateData['destination_address'] = $data->destination_address;
                    $updateData['destination_lat'] = $data->destination_lat;
                    $updateData['destination_lng'] = $data->destination_lng;
                }

                if ($hasLocationChange) {
                    $estimationData = CreateRideData::from([
                        'origin_address'      => $updateData['origin_address'] ?? $ride->origin_address,
                        'origin_lat'          => $updateData['origin_lat'] ?? $ride->origin_lat,
                        'origin_lng'          => $updateData['origin_lng'] ?? $ride->origin_lng,
                        'destination_address' => $updateData['destination_address'] ?? $ride->destination_address,
                        'destination_lat'     => $updateData['destination_lat'] ?? $ride->destination_lat,
                        'destination_lng'     => $updateData['destination_lng'] ?? $ride->destination_lng,
                    ]);

                    $estimates = $this->estimationService->calculateEstimates($estimationData);

                    $updateData['estimated_price'] = $estimates->price;
                    $updateData['estimated_distance_km'] = $estimates->distance_km;
                    $updateData['estimated_duration_min'] = $estimates->duration_minutes;
                }

                if ($data->scheduled_at !== null) {
                    $updateData['scheduled_at'] = $data->scheduled_at;
                }

                $ride->update($updateData);

                if ($data->scheduled_at !== null) {
                    $activationTime = $data->scheduled_at->subMinutes(10);
                    $delay = max(0, (int) now()->diffInSeconds($activationTime, false));

                    ActivateScheduledRideJob::dispatch($ride->id)
                        ->delay($delay);
                }

                return $ride->refresh();
            },
            attempts: 3,
        );
    }
}
