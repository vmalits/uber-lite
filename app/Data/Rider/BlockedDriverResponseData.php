<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Models\BlockedDriver;
use Spatie\LaravelData\Data;

final class BlockedDriverResponseData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $driver_id,
        public readonly string $first_name,
        public readonly string $last_name,
        /** @var array<string, string>|null */
        public readonly ?array $avatar_paths,
        public readonly DateData $created_at,
        public readonly DateData $updated_at,
    ) {}

    public static function fromModel(BlockedDriver $model): self
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
