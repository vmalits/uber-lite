<?php

declare(strict_types=1);

namespace App\Data\Driver;

use App\Enums\PayoutMethod;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class RequestPayoutData extends Data
{
    public function __construct(
        public int $amount,
        public PayoutMethod $method,
        #[MapName('bank_name')]
        public ?string $bankName = null,
        #[MapName('bank_account_number')]
        public ?string $bankAccountNumber = null,
        #[MapName('bank_routing_number')]
        public ?string $bankRoutingNumber = null,
        #[MapName('crypto_wallet_address')]
        public ?string $cryptoWalletAddress = null,
        #[MapName('crypto_currency')]
        public ?string $cryptoCurrency = null,
        public ?string $description = null,
    ) {}
}
