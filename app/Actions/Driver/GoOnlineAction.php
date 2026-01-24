<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\DriverRealtimeLocationData;
use App\Models\User;
use App\Services\Centrifugo\DriverLocationPublisher;
use App\Services\Driver\DriverLocationUpdater;

readonly class GoOnlineAction
{
    public function __construct(
        private DriverLocationUpdater $updater,
        private DriverLocationPublisher $publisher,
    ) {}

    public function handle(User $driver, float $latitude, float $longitude): DriverRealtimeLocationData
    {
        $result = $this->updater->update(
            driverId: $driver->id,
            lat: $latitude,
            lng: $longitude,
            timestamp: time(),
        );

        if ($result->shouldPublish) {
            $this->publisher->publish($result->snapshot);
        }

        return $result->snapshot;
    }
}
