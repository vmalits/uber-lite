<?php

declare(strict_types=1);

namespace App\Services\Driver;

use App\Data\Driver\DriverRealtimeLocationData;
use App\Enums\DriverAvailabilityStatus;

final readonly class DriverLocationUpdater
{
    public function __construct(
        private DriverLocationRedisStore $redisStore,
        private DriverLocationConfig $config,
    ) {}

    public function update(string $driverId, float $lat, float $lng, int $timestamp): DriverLocationUpdateResult
    {
        $result = $this->redisStore->updateLocation(
            driverId: $driverId,
            lng: $lng,
            lat: $lat,
            timestamp: $timestamp,
            ttl: $this->config->ttlSeconds(),
            publishMinInterval: $this->config->publishMinIntervalSeconds(),
            publishMinDistanceMeters: $this->config->publishMinDistanceMeters(),
            publishEnabled: $this->config->publishEnabled(),
        );

        $status = DriverAvailabilityStatus::tryFrom($result['status'])
            ?? DriverAvailabilityStatus::ONLINE;

        return new DriverLocationUpdateResult(
            snapshot: new DriverRealtimeLocationData(
                driver_id: $driverId,
                status: $status,
                lat: $result['lat'],
                lng: $result['lng'],
                ts: $result['ts'],
            ),
            shouldPublish: $result['publish'] === 1,
        );
    }
}
