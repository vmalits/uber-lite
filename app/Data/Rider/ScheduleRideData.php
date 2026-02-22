<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class ScheduleRideData extends Data
{
    public function __construct(
        public string $origin_address,
        public float $origin_lat,
        public float $origin_lng,
        public string $destination_address,
        public float $destination_lat,
        public float $destination_lng,
        #[WithCast(DateTimeInterfaceCast::class, format: ['Y-m-d H:i:s', 'Y-m-d\TH:i:sP'])]
        public CarbonImmutable $scheduled_at,
    ) {}
}
