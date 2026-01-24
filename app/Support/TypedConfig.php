<?php

declare(strict_types=1);

namespace App\Support;

final class TypedConfig
{
    public static function int(string $key, int $default): int
    {
        $value = config($key, $default);

        if (\is_int($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return $default;
    }

    public static function float(string $key, float $default): float
    {
        $value = config($key, $default);

        if (\is_float($value)) {
            return $value;
        }

        if (\is_int($value)) {
            return (float) $value;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        return $default;
    }

    public static function bool(string $key, bool $default): bool
    {
        $value = config($key, $default);

        if (\is_bool($value)) {
            return $value;
        }

        if (\is_int($value)) {
            return $value === 1;
        }

        if (\is_string($value)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? $default;
        }

        return $default;
    }

    public static function string(string $key, string $default): string
    {
        $value = config($key, $default);

        if (\is_string($value) && $value !== '') {
            return $value;
        }

        return $default;
    }
}
