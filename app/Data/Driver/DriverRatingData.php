<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Data;

final class DriverRatingData extends Data
{
    public function __construct(
        public int $rating,
        public ?string $comment = null,
    ) {}
}
