<?php

declare(strict_types=1);

namespace App\Actions\Driver;

use App\Data\Driver\DriverRealtimeLocationData;
use App\Enums\DriverAvailabilityStatus;
use App\Models\User;
use App\Services\Driver\DriverLocationRedisStore;

readonly class GoOfflineAction
{
    public function __construct(
        private DriverLocationRedisStore $redisStore,
    ) {}

    public function handle(User $driver): DriverRealtimeLocationData
    {
        $this->redisStore->markOffline($driver->id);

        return new DriverRealtimeLocationData(
            driver_id: $driver->id,
            status: DriverAvailabilityStatus::OFFLINE,
            lat: null,
            lng: null,
            ts: time(),
        );
    }
}
