<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverRealtimeLocationData;
use App\Enums\DriverAvailabilityStatus;
use App\Services\Driver\DriverLocationConfig;
use Illuminate\Contracts\Redis\Factory as RedisFactory;

final class GetCurrentLocationQuery implements GetCurrentLocationQueryInterface
{
    public function __construct(
        private RedisFactory $redis,
        private DriverLocationConfig $config,
    ) {}

    public function execute(string $driverId): ?DriverRealtimeLocationData
    {
        $connection = $this->redis->connection();
        $key = $this->config->locationKey($driverId);

        /** @var string|false $data */
        $data = $connection->command('get', [$key]);

        if ($data === false) {
            return null;
        }

        /** @var array{publish?: int, lat?: float, lng?: float, ts?: int, status?: string}|false $decoded */
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
