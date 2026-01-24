<?php

declare(strict_types=1);

namespace App\Services\Driver;

use App\Support\TypedConfig;

final class DriverLocationConfig
{
    public function geoKey(): string
    {
        return TypedConfig::string('driver_location.redis.geo_key', 'drivers:geo');
    }

    public function onlineKey(): string
    {
        return TypedConfig::string('driver_location.redis.online_key', 'drivers:online');
    }

    public function stateKey(string $driverId): string
    {
        return "driver:{$driverId}:state";
    }

    public function locationKey(string $driverId): string
    {
        return "driver:{$driverId}:location";
    }

    public function publishKey(string $driverId): string
    {
        return "driver:{$driverId}:publish";
    }

    public function ttlSeconds(): int
    {
        return TypedConfig::int('driver_location.redis.ttl', 30);
    }

    public function searchRadiusKm(): float
    {
        return TypedConfig::float('driver_location.redis.search_radius_km', 5.0);
    }

    public function searchCount(): int
    {
        return TypedConfig::int('driver_location.redis.search_count', 20);
    }

    public function publishEnabled(): bool
    {
        return TypedConfig::bool('driver_location.publish.enabled', true);
    }

    public function publishMinIntervalSeconds(): int
    {
        return TypedConfig::int('driver_location.publish.min_interval_seconds', 2);
    }

    public function publishMinDistanceMeters(): float
    {
        return TypedConfig::float('driver_location.publish.min_distance_meters', 10.0);
    }

    public function publishAdminRegion(): bool
    {
        return TypedConfig::bool('driver_location.publish.admin_region', true);
    }

    public function regionPrecision(): int
    {
        return TypedConfig::int('driver_location.publish.region_precision', 2);
    }
}
