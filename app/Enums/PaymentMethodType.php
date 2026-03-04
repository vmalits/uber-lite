<?php

declare(strict_types=1);

namespace App\Enums;

enum PaymentMethodType: string
{
    case CARD       = 'card';
    case APPLE_PAY  = 'apple_pay';
    case GOOGLE_PAY = 'google_pay';

    public function label(): string
    {
        return match ($this) {
            self::CARD       => 'Credit/Debit Card',
            self::APPLE_PAY  => 'Apple Pay',
            self::GOOGLE_PAY => 'Google Pay',
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
