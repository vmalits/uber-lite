<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Services\Driver\DriverLocationRedisStore;

final readonly class FindNearbyDriversQuery implements FindNearbyDriversQueryInterface
{
    public function __construct(
        private DriverLocationRedisStore $redisStore,
    ) {}

    public function execute(float $lng, float $lat, ?float $radiusKm = null, ?int $limit = null): array
    {
        return $this->redisStore->findNearbyDrivers(
            lng: $lng,
            lat: $lat,
            radiusKm: $radiusKm,
            limit: $limit,
        );
    }
}
