<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class SearchLocationsData extends Data
{
    public function __construct(
        public string $query,
        public int $limit = 5,
        public ?float $lat = null,
        public ?float $lng = null,
    ) {}

    /**
     * @return array{lat: float, lng: float}|null
     */
    public function getUserLocation(): ?array
    {
        if ($this->lat !== null && $this->lng !== null) {
            return [
                'lat' => $this->lat,
                'lng' => $this->lng,
            ];
        }

        return null;
    }
}
