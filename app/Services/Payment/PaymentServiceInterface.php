<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\PaymentAttempt;

interface PaymentServiceInterface
{
    public function tokenizeCard(
        string $token,
        string $lastFour,
        string $cardBrand,
        ?string $holderName = null,
    ): TokenizedCardResult;

    public function charge(PaymentAttempt $attempt): ChargeResult;
}
