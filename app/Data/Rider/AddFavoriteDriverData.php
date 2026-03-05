<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class AddFavoriteDriverData extends Data
{
    public function __construct(
        public string $driver_id,
    ) {}
}
