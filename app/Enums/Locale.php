<?php

declare(strict_types=1);

namespace App\Enums;

enum Locale: string
{
    case RO = 'ro';
    case RU = 'ru';
    case EN = 'en';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $locale): bool
    {
        return \in_array($locale, self::values(), true);
    }

    public static function fromValue(string $value): ?self
    {
        return array_find(self::cases(), fn ($case) => $case->value === $value);

    }
}
