<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Driver\DriverRealtimeLocationData;
use App\Data\Rider\TrackRideData;
use App\Enums\DriverAvailabilityStatus;
use App\Models\Ride;
use App\Services\Driver\DriverLocationConfig;
use Illuminate\Contracts\Redis\Factory as RedisFactory;

final readonly class GetRideTrackingQuery implements GetRideTrackingQueryInterface
{
    public function __construct(
        private RedisFactory $redis,
        private DriverLocationConfig $config,
    ) {}

    public function execute(Ride $ride): TrackRideData
    {
        return new TrackRideData(
            ride_id: $ride->id,
            ride_status: $ride->status,
            driver_id: $ride->driver_id,
            driver_location: $this->getDriverLocation($ride->driver_id),
            origin_lat: $ride->origin_lat,
            origin_lng: $ride->origin_lng,
            destination_lat: $ride->destination_lat,
            destination_lng: $ride->destination_lng,
        );
    }

    private function getDriverLocation(?string $driverId): ?DriverRealtimeLocationData
    {
        if ($driverId === null) {
            return null;
        }

        $key = $this->config->locationKey($driverId);

        /** @var string|false $data */
        $data = $this->redis->connection()->command('get', [$key]);

        if ($data === false) {
            return null;
        }

        /** @var array{status?: string, lat?: float, lng?: float, ts?: int}|false $decoded */
        $decoded = json_decode($data, true);

        if ($decoded === false) {
            return null;
        }

        return new DriverRealtimeLocationData(
            driver_id: $driverId,
            status: DriverAvailabilityStatus::from($decoded['status'] ?? 'offline'),
            lat: $decoded['lat'] ?? null,
            lng: $decoded['lng'] ?? null,
            ts: $decoded['ts'] ?? 0,
        );
    }
}
