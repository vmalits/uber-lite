<?php

declare(strict_types=1);

namespace App\Queries\Driver;

interface FindNearbyDriversQueryInterface
{
    /**
     * @return array<int, array{driver_id: string, distance_km: float}>
     */
    public function execute(float $lng, float $lat, ?float $radiusKm = null, ?int $limit = null): array;
}
