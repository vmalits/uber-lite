<?php

declare(strict_types=1);

namespace App\Enums;

enum GeocodingDriver: string
{
    case Fake = 'fake';
    case OSM = 'osm';
    case OpenStreetMap = 'openstreetmap';
    case Nominatim = 'nominatim';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $driver): bool
    {
        return \in_array($driver, self::values(), true);
    }

    public static function fromValue(string $value): ?self
    {
        return array_find(self::cases(), fn (self $case): bool => $case->value === $value);
    }
}
