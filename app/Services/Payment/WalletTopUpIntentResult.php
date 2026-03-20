<?php

declare(strict_types=1);

namespace App\Services\Payment;

final readonly class WalletTopUpIntentResult
{
    public function __construct(
        public string $paymentIntentId,
        public string $clientSecret,
        public bool $requiresAction = false,
        public ?string $errorMessage = null,
    ) {}

    public static function success(string $paymentIntentId, string $clientSecret): self
    {
        return new self(
            paymentIntentId: $paymentIntentId,
            clientSecret: $clientSecret,
        );
    }

    public static function requiresAction(string $paymentIntentId, string $clientSecret): self
    {
        return new self(
            paymentIntentId: $paymentIntentId,
            clientSecret: $clientSecret,
            requiresAction: true,
        );
    }

    public static function failure(string $errorMessage): self
    {
        return new self(
            paymentIntentId: '',
            clientSecret: '',
            errorMessage: $errorMessage,
        );
    }

    public function isSuccessful(): bool
    {
        return $this->errorMessage === null && $this->paymentIntentId !== '';
    }
}
