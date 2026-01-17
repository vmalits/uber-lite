<?php

declare(strict_types=1);

namespace App\Services\Geo;

use App\Data\Rider\LocationSuggestionData;

final readonly class FakeGeocodingService implements GeocodingServiceInterface
{
    /**
     * @return array<int, LocationSuggestionData>
     */
    public function search(string $query, int $limit = 5, ?array $userLocation = null): array
    {
        $results = [
            [
                'id'      => '1',
                'address' => 'bd. Stefan cel Mare si Sfant 1, Chisinau',
                'lat'     => 47.0147,
                'lng'     => 28.8316,
                'city'    => 'Chisinau',
                'country' => 'Moldova',
            ],
            [
                'id'      => '2',
                'address' => 'bd. Stefan cel Mare si Sfant 45, Chisinau',
                'lat'     => 47.0132,
                'lng'     => 28.8405,
                'city'    => 'Chisinau',
                'country' => 'Moldova',
            ],
            [
                'id'      => '3',
                'address' => 'str. Mihai Eminescu 12, Chisinau',
                'lat'     => 47.0256,
                'lng'     => 28.8234,
                'city'    => 'Chisinau',
                'country' => 'Moldova',
            ],
            [
                'id'      => '4',
                'address' => 'str. 31 August 1989 1, Chisinau',
                'lat'     => 47.0189,
                'lng'     => 28.8301,
                'city'    => 'Chisinau',
                'country' => 'Moldova',
            ],
            [
                'id'      => '5',
                'address' => 'Piata Marii Adunari Nationale, Chisinau',
                'lat'     => 47.0185,
                'lng'     => 28.8342,
                'city'    => 'Chisinau',
                'country' => 'Moldova',
            ],
        ];

        $normalizedQuery = $this->normalizeForSearch($query);

        $filtered = array_filter(
            $results, fn (array $location): bool => $this->matches($normalizedQuery, $location['address']) ||
            $this->matches($normalizedQuery, $location['city']) ||
            $this->matches($normalizedQuery, $location['country']),
        );

        $suggestions = array_map(
            fn (array $location): LocationSuggestionData => LocationSuggestionData::fromArray($location),
            array_values($filtered),
        );

        if ($userLocation !== null) {
            $suggestions = $this->sortByDistance($suggestions, $userLocation);
        }

        return \array_slice($suggestions, 0, $limit);
    }

    private function normalizeForSearch(string $text): string
    {
        $transliterated = $this->transliterate($text);

        return mb_strtolower($transliterated, 'UTF-8');
    }

    private function matches(string $normalizedQuery, string $text): bool
    {
        $normalizedText = $this->normalizeForSearch($text);

        return mb_stripos($normalizedText, $normalizedQuery, 0, 'UTF-8') !== false;
    }

    private function transliterate(string $text): string
    {
        $map = [
            'ă' => 'a', 'â' => 'a',
            'î' => 'i',
            'ș' => 's', 'ț' => 't',
            'Ă' => 'A', 'Â' => 'A',
            'Î' => 'I',
            'Ș' => 'S', 'Ț' => 'T',
        ];

        return strtr($text, $map);
    }

    /**
     * @param array<int, LocationSuggestionData> $suggestions
     * @param array{lat: float, lng: float} $userLocation
     *
     * @return array<int, LocationSuggestionData>
     */
    private function sortByDistance(array $suggestions, array $userLocation): array
    {
        $userLat = $userLocation['lat'];
        $userLng = $userLocation['lng'];

        usort($suggestions,
            function (LocationSuggestionData $a, LocationSuggestionData $b) use ($userLat, $userLng): int {
                $distA = $this->calculateDistance($a->lat, $a->lng, $userLat, $userLng);
                $distB = $this->calculateDistance($b->lat, $b->lng, $userLat, $userLng);

                return $distA <=> $distB;
            });

        return $suggestions;
    }

    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $theta = $lng1 - $lng2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        $dist = acos($dist);
        $dist = rad2deg($dist);

        return $dist * 60 * 1.1515 * 1.609344;
    }
}
