<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Enums\Currency;
use App\Enums\PaymentStatus;
use App\Models\PaymentAttempt;
use Spatie\LaravelData\Data;

final class PaymentAttemptData extends Data
{
    public function __construct(
        public string $id,
        public PaymentStatus $status,
        public int $amount,
        public int $credits_used,
        public int $card_amount,
        public Currency $currency,
        public ?string $provider,
        public ?string $provider_transaction_id,
        public ?string $failure_reason,
        public ?DateData $completed_at,
        public DateData $created_at,
    ) {}

    public static function fromModel(PaymentAttempt $attempt): self
    {
        return new self(
            id: $attempt->id,
            status: $attempt->status,
            amount: $attempt->amount,
            credits_used: $attempt->credits_used,
            card_amount: $attempt->card_amount,
            currency: $attempt->currency,
            provider: $attempt->provider,
            provider_transaction_id: $attempt->provider_transaction_id,
            failure_reason: $attempt->failure_reason,
            completed_at: $attempt->completed_at !== null ? DateData::fromCarbon($attempt->completed_at) : null,
            created_at: DateData::fromCarbon($attempt->created_at),
        );
    }
}
