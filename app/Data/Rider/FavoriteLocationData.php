<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Models\FavoriteLocation;
use Spatie\LaravelData\Data;

final class FavoriteLocationData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public float $lat,
        public float $lng,
        public string $address,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(FavoriteLocation $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            lat: $model->lat,
            lng: $model->lng,
            address: $model->address,
            created_at: DateData::fromCarbon($model->created_at),
            updated_at: DateData::fromCarbon($model->updated_at),
        );
    }
}
