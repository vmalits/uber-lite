<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Data;

final class DriverBalanceData extends Data
{
    public function __construct(
        public int $balance,
        public int $pending_payouts,
        public int $total_earned,
        public int $total_paid_out,
    ) {}
}
