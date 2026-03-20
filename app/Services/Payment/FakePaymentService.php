<?php

declare(strict_types=1);

namespace App\Services\Payment;

use App\Models\PaymentAttempt;
use Illuminate\Support\Str;

final readonly class FakePaymentService implements PaymentServiceInterface
{
    public function tokenizeCard(
        string $token,
        string $lastFour,
        string $cardBrand,
        ?string $holderName = null,
    ): TokenizedCardResult {
        return new TokenizedCardResult(
            providerToken: 'fake_pm_'.Str::random(24),
            lastFour: $lastFour,
            cardBrand: $cardBrand,
            expiryMonth: 12,
            expiryYear: (int) now()->addYear()->format('Y'),
            holderName: $holderName,
        );
    }

    public function charge(PaymentAttempt $attempt): ChargeResult
    {
        return new ChargeResult(
            successful: true,
            providerTransactionId: 'fake_ch_'.Str::random(24),
        );
    }

    public function createWalletTopUpIntent(
        int $amount,
        string $currency,
        ?string $paymentMethodId,
        string $customerId,
    ): WalletTopUpIntentResult {
        $paymentIntentId = 'pi_fake_'.Str::random(24);
        $clientSecret = $paymentIntentId.'_secret_'.Str::random(12);

        return WalletTopUpIntentResult::success(
            paymentIntentId: $paymentIntentId,
            clientSecret: $clientSecret,
        );
    }

    public function confirmWalletTopUpIntent(string $paymentIntentId): bool
    {
        return true;
    }

    public function cancelWalletTopUpIntent(string $paymentIntentId): bool
    {
        return true;
    }
}
