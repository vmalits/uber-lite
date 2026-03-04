<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Models\FavoriteDriver;
use Spatie\LaravelData\Data;

final class FavoriteDriverData extends Data
{
    public function __construct(
        public string $id,
        public string $driver_id,
        public string $first_name,
        public string $last_name,
        /** @var array<string, string>|null */
        public ?array $avatar_paths,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(FavoriteDriver $model): self
    {
        $driver = $model->driver;

        return new self(
            id: $model->id,
            driver_id: $driver->id,
            first_name: $driver->first_name ?? '',
            last_name: $driver->last_name ?? '',
            avatar_paths: $driver->avatar_paths,
            created_at: DateData::fromCarbon($model->created_at),
            updated_at: DateData::fromCarbon($model->updated_at),
        );
    }
}
