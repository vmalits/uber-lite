<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Enums\Currency;

final readonly class WalletBalanceData
{
    public function __construct(
        public int $balance,
        public Currency $currency,
        public int $pending_count,
    ) {}
}
