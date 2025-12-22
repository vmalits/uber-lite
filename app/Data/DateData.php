<?php

declare(strict_types=1);

namespace App\Data;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Data;

final class DateData extends Data
{
    public function __construct(
        public string $human,
        public string $string,
    ) {}

    public static function fromCarbon(CarbonInterface $date): self
    {
        return new self(
            human: $date->diffForHumans(),
            string: $date->toDateTimeString(),
        );
    }
}
