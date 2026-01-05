<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Data;

final class UnbanDriverData extends Data
{
    public function __construct(
        public ?string $unbannedBy,
        public ?string $reason = null,
    ) {}
}
