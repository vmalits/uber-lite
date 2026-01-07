<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class RateRideData extends Data
{
    public function __construct(
        public int $rating,
        public ?string $comment,
    ) {}
}
