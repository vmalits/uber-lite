<?php

declare(strict_types=1);

namespace App\Data\Admin;

use Spatie\LaravelData\Data;

final class MonthlyRevenueData extends Data
{
    public function __construct(
        public string $month,
        public int $revenue,
        public int $rides,
    ) {}
}
