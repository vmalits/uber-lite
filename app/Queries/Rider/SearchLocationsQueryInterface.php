<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\LocationSuggestionData;
use App\Data\Rider\SearchLocationsData;

interface SearchLocationsQueryInterface
{
    /**
     * @return array<int, LocationSuggestionData>
     */
    public function execute(SearchLocationsData $data): array;
}
