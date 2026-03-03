<?php

declare(strict_types=1);

namespace App\Data\Driver;

use App\Data\DateData;
use App\Enums\PayoutMethod;
use App\Enums\PayoutStatus;
use App\Models\DriverPayout;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class DriverPayoutData extends Data
{
    public function __construct(
        public string $id,
        public int $amount,
        public PayoutStatus $status,
        public PayoutMethod $method,
        public ?string $bank_name,
        #[MapName('bank_account_number')]
        public ?string $bankAccountNumber,
        #[MapName('bank_routing_number')]
        public ?string $bankRoutingNumber,
        #[MapName('crypto_wallet_address')]
        public ?string $cryptoWalletAddress,
        #[MapName('crypto_currency')]
        public ?string $cryptoCurrency,
        public ?DateData $requested_at,
        public ?DateData $approved_at,
        public ?DateData $processed_at,
        public ?DateData $completed_at,
        public ?DateData $failed_at,
        public ?string $failure_reason,
        public ?string $description,
        public DateData $created_at,
    ) {}

    public static function fromModel(DriverPayout $payout): self
    {
        return new self(
            id: $payout->id,
            amount: $payout->amount,
            status: $payout->status,
            method: $payout->method,
            bank_name: $payout->bank_name,
            bankAccountNumber: $payout->bank_account_number,
            bankRoutingNumber: $payout->bank_routing_number,
            cryptoWalletAddress: $payout->crypto_wallet_address,
            cryptoCurrency: $payout->crypto_currency,
            requested_at: $payout->requested_at ? DateData::fromCarbon($payout->requested_at) : null,
            approved_at: $payout->approved_at ? DateData::fromCarbon($payout->approved_at) : null,
            processed_at: $payout->processed_at ? DateData::fromCarbon($payout->processed_at) : null,
            completed_at: $payout->completed_at ? DateData::fromCarbon($payout->completed_at) : null,
            failed_at: $payout->failed_at ? DateData::fromCarbon($payout->failed_at) : null,
            failure_reason: $payout->failure_reason,
            description: $payout->description,
            created_at: DateData::fromCarbon($payout->created_at),
        );
    }
}
