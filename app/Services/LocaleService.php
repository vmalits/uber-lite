<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Locale;
use Illuminate\Foundation\Application;

final readonly class LocaleService
{
    public function __construct(
        private Application $app,
    ) {}

    /**
     * @return array<int, string>
     */
    public function supported(): array
    {
        return Locale::values();
    }

    public function isValid(string $locale): bool
    {
        return Locale::isValid($locale);
    }

    public function current(): string
    {
        return $this->app->getLocale();
    }

    /**
     * @param array<string, string> $replace
     */
    public function message(string $key, array $replace = []): string
    {
        /** @var string $message */
        $message = trans($key, $replace);

        return $message;
    }
}
