<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Locale;

final readonly class LocaleNegotiator
{
    /**
     * @param array<int|string, string> $acceptLanguages
     */
    public function negotiate(?Locale $userLocale, array $acceptLanguages): string
    {
        if ($userLocale !== null) {
            return $userLocale->value;
        }

        foreach ($acceptLanguages as $lang) {
            $normalized = str_replace('_', '-', $lang);

            if (Locale::isValid($normalized)) {
                return $normalized;
            }

            if (str_contains($normalized, '-')) {
                $short = explode('-', $normalized)[0];
                if (Locale::isValid($short)) {
                    return $short;
                }
            }
        }

        /** @var string $default */
        $default = config('app.locale');

        return $default;
    }
}
