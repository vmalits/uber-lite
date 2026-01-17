<?php

declare(strict_types=1);

namespace App\Services\Geo;

use App\Data\Rider\LocationSuggestionData;
use Illuminate\Http\Client\Factory;
use Psr\Log\LoggerInterface;

abstract readonly class AbstractGeocodingService implements GeocodingServiceInterface
{
    protected const string USER_AGENT = 'UberLite/1.0';

    public function __construct(
        protected Factory $http,
        protected LoggerInterface $logger,
    ) {}

    /**
     * @return array<int, LocationSuggestionData>
     */
    abstract public function search(string $query, int $limit = 5, ?array $userLocation = null): array;

    /**
     * @param array<int, LocationSuggestionData> $suggestions
     * @param array{lat: float, lng: float} $userLocation
     *
     * @return array<int, LocationSuggestionData>
     */
    protected function sortByDistance(array $suggestions, array $userLocation): array
    {
        $userLat = $userLocation['lat'];
        $userLng = $userLocation['lng'];

        usort($suggestions, function (
            LocationSuggestionData $a,
            LocationSuggestionData $b,
        ) use ($userLat, $userLng): int {
            $distA = $this->calculateDistance($a->lat, $a->lng, $userLat, $userLng);
            $distB = $this->calculateDistance($b->lat, $b->lng, $userLat, $userLng);

            return $distA <=> $distB;
        });

        return $suggestions;
    }

    protected function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $theta = $lng1 - $lng2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        $dist = acos($dist);
        $dist = rad2deg($dist);

        return $dist * 60 * 1.1515 * 1.609344;
    }

    /**
     * @param array<int, array<string, mixed>> $data
     *
     * @return array<int, LocationSuggestionData>
     */
    protected function parseResponseArray(array $data): array
    {
        return array_values(array_map(
            fn (array $item): LocationSuggestionData => $this->parseResponse($item),
            $data,
        ));
    }

    /**
     * @param array<string, mixed> $item
     */
    protected function parseResponse(array $item): LocationSuggestionData
    {
        $address = \is_array($item['address'] ?? null) ? $item['address'] : [];
        $displayName = \is_string($item['display_name'] ?? null) ? $item['display_name'] : '';

        $lat = is_numeric($item['lat'] ?? null) ? (float) $item['lat'] : 0.0;
        $lon = is_numeric($item['lon'] ?? null) ? (float) $item['lon'] : 0.0;

        $city = isset($address['city']) && \is_string($address['city']) ? $address['city'] : (
            isset($address['town']) && \is_string($address['town']) ? $address['town'] : (
                isset($address['village']) && \is_string($address['village']) ? $address['village'] : (
                    isset($address['municipality']) && \is_string($address['municipality']) ? $address['municipality'] : null
                )
            )
        );
        $country = isset($address['country']) && \is_string($address['country']) ? $address['country'] : null;

        return LocationSuggestionData::fromArray([
            'id'      => \is_string($item['place_id'] ?? null) ? $item['place_id'] : md5($displayName),
            'address' => $displayName,
            'lat'     => $lat,
            'lng'     => $lon,
            'city'    => $city,
            'country' => $country,
        ]);
    }
}
