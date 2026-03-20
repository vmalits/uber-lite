<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Enums\Currency;
use App\Models\WalletTopUp;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param int $amount
 * @param Currency $currency
 * @param string $status
 * @param string|null $client_secret
 * @param string|null $payment_intent_id
 * @param DateData|null $completed_at
 * @param DateData $created_at
 */
final class WalletTopUpData extends Data
{
    public function __construct(
        public string $id,
        public int $amount,
        public Currency $currency,
        public string $status,
        public ?string $client_secret,
        public ?string $payment_intent_id,
        public ?DateData $completed_at,
        public DateData $created_at,
    ) {}

    public static function fromModel(WalletTopUp $topUp, ?string $clientSecret = null): self
    {
        return new self(
            id: $topUp->id,
            amount: $topUp->amount,
            currency: $topUp->currency,
            status: $topUp->status->value,
            client_secret: $clientSecret,
            payment_intent_id: $topUp->payment_intent_id,
            completed_at: $topUp->completed_at ? DateData::fromCarbon($topUp->completed_at) : null,
            created_at: DateData::fromCarbon($topUp->created_at),
        );
    }
}
