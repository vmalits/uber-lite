<?php

declare(strict_types=1);

namespace App\Enums;

enum PayoutMethod: string
{
    case BANK_TRANSFER = 'bank_transfer';
    case CRYPTO_WALLET = 'crypto_wallet';

    public function label(): string
    {
        return match ($this) {
            self::BANK_TRANSFER => 'Bank Transfer',
            self::CRYPTO_WALLET => 'Crypto Wallet',
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
