<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Models\FavoriteRoute;
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
        public ?string $id = null,
        public ?DateData $createdAt = null,
        public ?DateData $updatedAt = null,
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

    public static function fromModel(FavoriteRoute $model): self
    {
        return new self(
            name: $model->name,
            originAddress: $model->origin_address,
            originLat: $model->origin_lat,
            originLng: $model->origin_lng,
            destinationAddress: $model->destination_address,
            destinationLat: $model->destination_lat,
            destinationLng: $model->destination_lng,
            type: $model->type?->value,
            id: $model->id,
            createdAt: DateData::fromCarbon($model->created_at),
            updatedAt: DateData::fromCarbon($model->updated_at),
        );
    }
}
