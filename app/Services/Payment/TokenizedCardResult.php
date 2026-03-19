<?php

declare(strict_types=1);

namespace App\Services\Payment;

final readonly class TokenizedCardResult
{
    public function __construct(
        public string $providerToken,
        public string $lastFour,
        public string $cardBrand,
        public int $expiryMonth,
        public int $expiryYear,
        public ?string $holderName = null,
    ) {}
}
