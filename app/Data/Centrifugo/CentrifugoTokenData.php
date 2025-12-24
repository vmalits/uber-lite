<?php

declare(strict_types=1);

namespace App\Data\Centrifugo;

use Spatie\LaravelData\Data;

final class CentrifugoTokenData extends Data
{
    public function __construct(
        public string $token,
    ) {}
}
