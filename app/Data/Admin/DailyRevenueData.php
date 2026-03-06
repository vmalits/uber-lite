<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Data;

final class DailyRevenueData extends Data
{
    public function __construct(
        public string $date,
        public int $revenue,
        public int $rides,
    ) {}
}
