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
        #[MapName('bank_name')]
        public ?string $bankName,
        #[MapName('bank_account_number')]
        public ?string $bankAccountNumber,
        #[MapName('bank_routing_number')]
        public ?string $bankRoutingNumber,
        #[MapName('crypto_wallet_address')]
        public ?string $cryptoWalletAddress,
        #[MapName('crypto_currency')]
        public ?string $cryptoCurrency,
        #[MapName('requested_at')]
        public ?DateData $requestedAt,
        #[MapName('approved_at')]
        public ?DateData $approvedAt,
        #[MapName('processed_at')]
        public ?DateData $processedAt,
        #[MapName('completed_at')]
        public ?DateData $completedAt,
        #[MapName('failed_at')]
        public ?DateData $failedAt,
        #[MapName('failure_reason')]
        public ?string $failureReason,
        public ?string $description,
        #[MapName('created_at')]
        public DateData $createdAt,
    ) {}

    public static function fromModel(DriverPayout $payout): self
    {
        return new self(
            id: $payout->id,
            amount: $payout->amount,
            status: $payout->status,
            method: $payout->method,
            bankName: $payout->bank_name,
            bankAccountNumber: $payout->bank_account_number,
            bankRoutingNumber: $payout->bank_routing_number,
            cryptoWalletAddress: $payout->crypto_wallet_address,
            cryptoCurrency: $payout->crypto_currency,
            requestedAt: $payout->requested_at ? DateData::fromCarbon($payout->requested_at) : null,
            approvedAt: $payout->approved_at ? DateData::fromCarbon($payout->approved_at) : null,
            processedAt: $payout->processed_at ? DateData::fromCarbon($payout->processed_at) : null,
            completedAt: $payout->completed_at ? DateData::fromCarbon($payout->completed_at) : null,
            failedAt: $payout->failed_at ? DateData::fromCarbon($payout->failed_at) : null,
            failureReason: $payout->failure_reason,
            description: $payout->description,
            createdAt: DateData::fromCarbon($payout->created_at),
        );
    }
}
