<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class DriverBalanceData extends Data
{
    public function __construct(
        public int $balance,
        #[MapName('pending_payouts')]
        public int $pendingPayouts,
        #[MapName('total_earned')]
        public int $totalEarned,
        #[MapName('total_paid_out')]
        public int $totalPaidOut,
    ) {}
}
