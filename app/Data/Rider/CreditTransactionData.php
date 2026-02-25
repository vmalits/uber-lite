<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Enums\CreditTransactionType;
use App\Models\CreditTransaction;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param int $amount
 * @param int $balance_after
 * @param CreditTransactionType $type
 * @param string $description
 * @param DateData $created_at
 */
final class CreditTransactionData extends Data
{
    public function __construct(
        public string $id,
        public int $amount,
        public int $balance_after,
        public CreditTransactionType $type,
        public string $description,
        public DateData $created_at,
    ) {}

    public static function fromModel(CreditTransaction $transaction): self
    {
        return new self(
            id: $transaction->id,
            amount: $transaction->amount,
            balance_after: $transaction->balance_after,
            type: $transaction->type,
            description: $transaction->description,
            created_at: DateData::fromCarbon($transaction->created_at),
        );
    }
}
