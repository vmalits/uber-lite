<?php

declare(strict_types=1);

namespace App\Services\Payment;

final readonly class ChargeResult
{
    public function __construct(
        public bool $successful,
        public ?string $providerTransactionId = null,
        public ?string $failureReason = null,
    ) {}
}
