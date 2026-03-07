<?php

declare(strict_types=1);

namespace App\Dto\Rider;

use Spatie\LaravelData\Data;

final class SurgeData extends Data
{
    public function __construct(
        public float $multiplier,
        public string $reason,
        public bool $is_active,
    ) {}

    public static function default(): self
    {
        return new self(
            multiplier: 1.0,
            reason: 'normal',
            is_active: false,
        );
    }
}
