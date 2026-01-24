<?php

declare(strict_types=1);

namespace App\Services\Driver;

use Illuminate\Contracts\Redis\Factory as RedisFactory;
use JsonException;
use Throwable;

final readonly class DriverLocationRedisStore
{
    public function __construct(
        private RedisFactory $redis,
        private DriverLocationConfig $config,
    ) {}

    /**
     * @throws JsonException|Throwable
     *
     * @return array{publish: int, lat: float, lng: float, ts: int, status: string}
     */
    public function updateLocation(
        string $driverId,
        float $lng,
        float $lat,
        int $timestamp,
        int $ttl,
        int $publishMinInterval,
        float $publishMinDistanceMeters,
        bool $publishEnabled,
    ): array {
        $args = [
            $this->config->geoKey(),
            $this->config->onlineKey(),
            $this->config->stateKey($driverId),
            $this->config->locationKey($driverId),
            $this->config->publishKey($driverId),
            $driverId,
            (string) $lng,
            (string) $lat,
            (string) $timestamp,
            (string) $ttl,
            (string) $publishMinInterval,
            (string) $publishMinDistanceMeters,
            $publishEnabled ? '1' : '0',
        ];

        $connection = $this->redis->connection();
        $result = $connection->command('eval', [$this->updateLua(), $args, 5]);

        /** @var array<array-key, mixed>|null $decoded */
        $decoded = \is_string($result) ? json_decode(
            $result, true, 512, JSON_THROW_ON_ERROR,
        ) : null;

        if (! \is_array($decoded)) {
            return [
                'publish' => 0,
                'lat'     => $lat,
                'lng'     => $lng,
                'ts'      => $timestamp,
                'status'  => 'online',
            ];
        }

        return [
            'publish' => $this->readInt($decoded, 'publish', 0),
            'lat'     => $this->readFloat($decoded, 'lat', $lat),
            'lng'     => $this->readFloat($decoded, 'lng', $lng),
            'ts'      => $this->readInt($decoded, 'ts', $timestamp),
            'status'  => $this->readString($decoded, 'status', 'online'),
        ];
    }

    /**
     * @throws Throwable
     *
     * @return array<int, array{driver_id: string, distance_km: float}>
     */
    public function findNearbyDrivers(float $lng, float $lat, ?float $radiusKm = null, ?int $limit = null): array
    {
        $radiusKm ??= $this->config->searchRadiusKm();
        $limit ??= $this->config->searchCount();

        $results = $this->redis->connection()->command('georadius', [
            $this->config->geoKey(),
            $lng,
            $lat,
            $radiusKm,
            'km',
            'WITHDIST',
            'COUNT',
            $limit,
            'ASC',
        ]);

        if (! \is_array($results) || $results === []) {
            return [];
        }

        $ids = [];
        $distances = [];

        foreach ($results as $row) {
            if (\is_array($row)) {
                if (! isset($row[0]) || ! \is_string($row[0]) || $row[0] === '') {
                    continue;
                }
                $id = $row[0];
                $ids[] = $id;
                $distances[$id] = isset($row[1]) && is_numeric($row[1])
                    ? (float) $row[1]
                    : 0.0;
            } elseif (\is_string($row) && $row !== '') {
                $ids[] = $row;
                $distances[$row] = 0.0;
            }
        }

        if ($ids === []) {
            return [];
        }

        $stateKeys = array_map(
            fn (string $id): string => $this->config->stateKey($id),
            $ids,
        );

        $states = $this->redis->connection()->mget($stateKeys);

        $drivers = [];

        foreach ($ids as $index => $id) {
            if (($states[$index] ?? null) === 'online') {
                $drivers[] = [
                    'driver_id'   => $id,
                    'distance_km' => $distances[$id] ?? 0.0,
                ];
            }
        }

        return $drivers;
    }

    /**
     * @throws Throwable
     */
    public function markOffline(string $driverId): void
    {
        $connection = $this->redis->connection();
        $connection->command('srem', [$this->config->onlineKey(), $driverId]);
        $connection->command('zrem', [$this->config->geoKey(), $driverId]);
        $connection->command('del', [$this->config->stateKey($driverId)]);
        $connection->command('del', [$this->config->locationKey($driverId)]);
        $connection->command('del', [$this->config->publishKey($driverId)]);
    }

    private function updateLua(): string
    {
        static $loaded = false;
        /** @var string $script */
        static $script = '';

        if (! $loaded) {
            $path = base_path('resources/redis/update_driver_location.lua');
            $contents = file_get_contents($path);
            $script = \is_string($contents) ? $contents : '';
            $loaded = true;
        }

        return $script;
    }

    /**
     * @param array<array-key, mixed> $data
     */
    private function readInt(array $data, string $key, int $default): int
    {
        $value = $data[$key] ?? null;

        if (\is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return $default;
    }

    /**
     * @param array<array-key, mixed> $data
     */
    private function readFloat(array $data, string $key, float $default): float
    {
        $value = $data[$key] ?? null;

        if (\is_float($value)) {
            return $value;
        }

        if (\is_int($value)) {
            return (float) $value;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return $default;
    }

    /**
     * @param array<array-key, mixed> $data
     */
    private function readString(array $data, string $key, string $default): string
    {
        $value = $data[$key] ?? null;

        if (\is_string($value) && $value !== '') {
            return $value;
        }

        return $default;
    }
}
