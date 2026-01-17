<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class LocationSuggestionData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $address,
        public readonly float $lat,
        public readonly float $lng,
        public readonly ?string $city,
        public readonly ?string $country,
    ) {}

    /**
     * Create from array (with minimal validation)
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: \is_string($data['id'] ?? null) ? $data['id'] : '',
            address: \is_string($data['address'] ?? null) ? $data['address'] : '',
            lat: is_numeric($data['lat'] ?? null) ? (float) $data['lat'] : 0.0,
            lng: is_numeric($data['lng'] ?? null) ? (float) $data['lng'] : 0.0,
            city: isset($data['city']) && \is_string($data['city']) ? $data['city'] : null,
            country: isset($data['country']) && \is_string($data['country']) ? $data['country'] : null,
        );
    }
}
