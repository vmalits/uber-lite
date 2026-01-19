<?php

declare(strict_types=1);

namespace App\Services\Geo;

use App\Data\Rider\LocationSuggestionData;
use Throwable;

final readonly class OpenStreetMapGeocodingService extends AbstractGeocodingService
{
    private const string NOMINATIM_API = 'https://nominatim.openstreetmap.org/search';

    /**
     * @return array<int, LocationSuggestionData>
     */
    public function search(string $query, int $limit = 5, ?array $userLocation = null): array
    {
        try {
            $response = $this->http->acceptJson()
                ->withHeaders(['User-Agent' => self::USER_AGENT])
                ->get(self::NOMINATIM_API, [
                    'q'              => $query,
                    'format'         => 'json',
                    'limit'          => $limit,
                    'addressdetails' => 1,
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
            ]);

            return [];
        }
    }
}
