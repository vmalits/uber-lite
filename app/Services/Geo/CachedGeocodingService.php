<?php

declare(strict_types=1);

namespace App\Services\Geo;

use App\Data\Rider\LocationSuggestionData;
use Illuminate\Contracts\Cache\Repository;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\InvalidArgumentException;

final readonly class CachedGeocodingService implements GeocodingServiceInterface
{
    public function __construct(
        private GeocodingServiceInterface $decorated,
        private Repository $cache,
        private int $cacheTtl,
        private LoggerInterface $logger,
    ) {}

    /**
     * @throws InvalidArgumentException
     *
     * @return array<int, LocationSuggestionData>
     */
    public function search(string $query, int $limit = 5, ?array $userLocation = null): array
    {
        $cacheKey = $this->getCacheKey($query, $limit, $userLocation);

        $cached = $this->cache->get($cacheKey);
        if (\is_array($cached) && array_is_list($cached)) {
            $result = array_filter($cached, function ($item) {
                if (! \is_array($item)) {
                    return false;
                }
                foreach (array_keys($item) as $key) {
                    if (! \is_string($key)) {
                        return false;
                    }
                }

                return true;
            });
            /** @var array<int, array<string, mixed>> $result */
            $hydrated = array_map(fn ($item) => LocationSuggestionData::fromArray($item), $result);
            if (\count($hydrated) === \count($cached)) {
                $this->logger->debug('Geocoding cache hit', ['query' => $query]);

                return $hydrated;
            }
        }

        $this->logger->debug('Geocoding cache miss', ['query' => $query]);

        $results = $this->decorated->search($query, $limit, $userLocation);

        $this->cache->put($cacheKey, $results, $this->cacheTtl);

        return $results;
    }

    /**
     * @param array{lat: float, lng: float}|null $userLocation
     */
    private function getCacheKey(string $query, int $limit, ?array $userLocation): string
    {
        $locationPart = $userLocation !== null
            ? \sprintf('lat:%.6f_lng:%.6f', $userLocation['lat'], $userLocation['lng'])
            : 'no_location';

        $hash = md5($query.$limit.$locationPart);

        return "geocoding:search:{$hash}";
    }
}
