<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentProvider: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';

    public function label(): string
    {
        return match ($this) {
            self::STRIPE => 'Stripe',
            self::PAYPAL => 'PayPal',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
