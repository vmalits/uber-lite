<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\DriverRealtimeLocationData;
use App\Data\Driver\UpdateLocationData;
use App\Models\User;
use App\Services\Centrifugo\DriverLocationPublisher;
use App\Services\Driver\DriverLocationUpdater;

final readonly class UpdateLocationAction
{
    public function __construct(
        private DriverLocationPublisher $publisher,
        private DriverLocationUpdater $updater,
    ) {}

    public function handle(User $driver, UpdateLocationData $data): DriverRealtimeLocationData
    {
        $result = $this->updater->update(
            driverId: $driver->id,
            lat: $data->lat,
            lng: $data->lng,
            timestamp: time(),
        );

        if ($result->shouldPublish) {
            $this->publisher->publish($result->snapshot);
        }

        return $result->snapshot;
    }
}
