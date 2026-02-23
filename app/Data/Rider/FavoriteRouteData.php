<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class FavoriteRouteData extends Data
{
    public function __construct(
        public string $name,
        public string $originAddress,
        public float $originLat,
        public float $originLng,
        public string $destinationAddress,
        public float $destinationLat,
        public float $destinationLng,
        public ?string $type = null,
    ) {}

    /**
     * @param array{name: string, origin_address: string, origin_lat: float, origin_lng: float, destination_address: string, destination_lat: float, destination_lng: float, type?: string|null} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            originAddress: $data['origin_address'],
            originLat: $data['origin_lat'],
            originLng: $data['origin_lng'],
            destinationAddress: $data['destination_address'],
            destinationLat: $data['destination_lat'],
            destinationLng: $data['destination_lng'],
            type: $data['type'] ?? null,
        );
    }
}
