<?php

declare(strict_types=1);

namespace App\Enums;

enum CreditTransactionType: string
{
    case REFERRAL_BONUS = 'referral_bonus';
    case PROMO_SAVING = 'promo_saving';
    case RIDE_PAYMENT = 'ride_payment';
    case ADMIN_ADJUSTMENT = 'admin_adjustment';

    public function label(): string
    {
        return match ($this) {
            self::REFERRAL_BONUS   => 'Referral Bonus',
            self::PROMO_SAVING     => 'Promo Code Saving',
            self::RIDE_PAYMENT     => 'Ride Payment',
            self::ADMIN_ADJUSTMENT => 'Admin Adjustment',
        };
    }
}
