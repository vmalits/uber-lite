<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\LocationSuggestionData;
use App\Data\Rider\SearchLocationsData;
use App\Services\Geo\GeocodingServiceInterface;

final readonly class SearchLocationsQuery implements SearchLocationsQueryInterface
{
    public function __construct(
        private GeocodingServiceInterface $geocodingService,
    ) {}

    /**
     * @return array<int, LocationSuggestionData>
     */
    public function execute(SearchLocationsData $data): array
    {
        return $this->geocodingService->search(
            query: $data->query,
            limit: $data->limit,
            userLocation: $data->getUserLocation(),
        );
    }
}
