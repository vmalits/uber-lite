<?php

declare(strict_types=1);

namespace App\Enums;

enum AvatarSize: string
{
    case SMALL = 'sm';
    case MEDIUM = 'md';
    case LARGE = 'lg';

    public function pixels(): int
    {
        return match ($this) {
            self::SMALL  => 64,
            self::MEDIUM => 256,
            self::LARGE  => 512,
        };
    }

    public function path(): string
    {
        return "avatar_{$this->value}.webp";
    }

    /** @return array<int, string> */
    public static function allPaths(): array
    {
        return array_map(
            static fn (self $case) => $case->path(),
            self::cases(),
        );
    }
}
