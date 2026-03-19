<?php

declare(strict_types=1);

namespace App\Enums;

enum Currency: string
{
    case MDL = 'MDL';
    case USD = 'USD';
    case EUR = 'EUR';
    case RON = 'RON';

    public function label(): string
    {
        return match ($this) {
            self::MDL => 'Moldovan Leu',
            self::USD => 'US Dollar',
            self::EUR => 'Euro',
            self::RON => 'Romanian Leu',
        };
    }

    public function symbol(): string
    {
        return match ($this) {
            self::MDL => 'L',
            self::USD => '$',
            self::EUR => '€',
            self::RON => 'lei',
        };
    }
}
