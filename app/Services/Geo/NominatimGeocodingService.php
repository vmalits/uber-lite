<?php

declare(strict_types=1);

namespace App\Services\Geo;

use App\Data\Rider\LocationSuggestionData;
use Illuminate\Http\Client\Factory;
use Psr\Log\LoggerInterface;
use Throwable;

final readonly class NominatimGeocodingService extends AbstractGeocodingService
{
    public function __construct(
        private string $baseUrl,
        Factory $http,
        LoggerInterface $logger,
    ) {
        parent::__construct($http, $logger);
    }

    public static function fromConfig(Factory $http, LoggerInterface $logger): self
    {
        $url = config('services.geocoding.nominatim.url', 'http://localhost:8081');

        return new self(
            baseUrl: \is_string($url) ? $url : 'http://localhost:8081',
            http: $http,
            logger: $logger,
        );
    }

    /**
     * @return array<int, LocationSuggestionData>
     */
    public function search(string $query, int $limit = 5, ?array $userLocation = null): array
    {
        try {
            $response = $this->http->acceptJson()
                ->withHeaders(['User-Agent' => self::USER_AGENT])
                ->get($this->baseUrl.'/search', [
                    'q'              => $query,
                    'format'         => 'json',
                    'limit'          => $limit,
                    'addressdetails' => 1,
                    'countrycodes'   => 'MD',
                    'dedupe'         => 1,
                ]);

            if (! $response->successful()) {
                $this->logger->warning('Nominatim API error', [
                    'query'  => $query,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);

                return [];
            }

            $data = $response->json();

            if (! \is_array($data)) {
                return [];
            }
            // Ensure $data is an array<int, array<string, mixed>>
            $data = array_values(array_filter($data, static function (mixed $item): bool {
                if (! \is_array($item)) {
                    return false;
                }

                return array_all(array_keys($item), static fn (mixed $key): bool => \is_string($key));
            }));
            /** @var array<int, array<string, mixed>> $data */
            $suggestions = $this->parseResponseArray($data);

            if ($userLocation !== null) {
                $suggestions = $this->sortByDistance($suggestions, $userLocation);
            }

            return \array_slice($suggestions, 0, $limit);
        } catch (Throwable $e) {
            $this->logger->error('Nominatim search failed', [
                'query' => $query,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [];
        }
    }
}
