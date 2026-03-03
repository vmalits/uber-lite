<?php

declare(strict_types=1);

namespace App\Data\Driver;

use App\Enums\PayoutMethod;
use Spatie\LaravelData\Data;

final class RequestPayoutData extends Data
{
    public function __construct(
        public int $amount,
        public PayoutMethod $method,
        public ?string $bankName = null,
        public ?string $bankAccountNumber = null,
        public ?string $bankRoutingNumber = null,
        public ?string $cryptoWalletAddress = null,
        public ?string $cryptoCurrency = null,
        public ?string $description = null,
    ) {}
}
