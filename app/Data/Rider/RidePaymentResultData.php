<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Enums\PaymentStatus;
use Spatie\LaravelData\Data;

final class RidePaymentResultData extends Data
{
    public function __construct(
        public string $payment_attempt_id,
        public PaymentStatus $status,
        public int $amount_paid,
        public int $credits_used,
        public int $card_charged,
        public int $remaining_balance,
        public bool $fully_paid,
    ) {}
}
