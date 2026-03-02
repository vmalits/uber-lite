<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Spatie\LaravelData\Data;

final class TipData extends Data
{
    public function __construct(
        public int $amount,
        public ?string $comment = null,
    ) {}
}
