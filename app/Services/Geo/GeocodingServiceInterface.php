<?php

declare(strict_types=1);

namespace App\Services\Geo;

use App\Data\Rider\LocationSuggestionData;

interface GeocodingServiceInterface
{
    /**
     * Search for locations by address/query
     *
     * @param string $query Search query
     * @param int $limit Maximum number of results
     * @param array{lat: float, lng: float}|null $userLocation User's current location for prioritizing nearby results
     *
     * @return array<int, LocationSuggestionData>
     */
    public function search(string $query, int $limit = 5, ?array $userLocation = null): array;
}
