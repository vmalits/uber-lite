<?php

declare(strict_types=1);

namespace App\Data\Rider;

use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class UpdateScheduledRideData extends Data
{
    public function __construct(
        public ?string $origin_address = null,
        public ?float $origin_lat = null,
        public ?float $origin_lng = null,
        public ?string $destination_address = null,
        public ?float $destination_lat = null,
        public ?float $destination_lng = null,
        #[WithCast(DateTimeInterfaceCast::class, format: ['Y-m-d H:i:s', 'Y-m-d\TH:i:sP'])]
        public ?CarbonImmutable $scheduled_at = null,
    ) {}
}
