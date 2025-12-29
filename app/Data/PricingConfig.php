<?php

declare(strict_types=1);

namespace App\Data;

final readonly class PricingConfig
{
    public function __construct(
        public float $baseFare,
        public float $perKm,
        public float $perMinute,
        public float $minimumFare,
    ) {}

    public static function fromConfig(): self
    {
        $getFloat = static function (string $path, float $default = 0.0): float {
            $value = config($path, $default);

            return is_numeric($value) ? (float) $value : $default;
        };

        return new self(
            baseFare: $getFloat('pricing.estimation.base_fare', 15.0),
            perKm: $getFloat('pricing.estimation.per_km', 8.0),
            perMinute: $getFloat('pricing.estimation.per_minute', 2.0),
            minimumFare: $getFloat('pricing.estimation.minimum_fare', 20.0),
        );
    }
}
