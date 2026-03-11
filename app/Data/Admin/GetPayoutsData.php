<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Data;

final class GetPayoutsData extends Data
{
    public function __construct(
        public int $perPage = 15,
        public ?string $status = null,
        public ?string $from = null,
        public ?string $to = null,
        public ?string $driverId = null,
    ) {}
}
