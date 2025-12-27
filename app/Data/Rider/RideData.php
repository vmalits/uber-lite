<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\Ride;
use Spatie\LaravelData\Data;

final class RideData extends Data
{
    public function __construct(
        public string $id,
        public string $rider_id,
        public ?string $driver_id,
        public string $origin_address,
        public ?float $origin_lat,
        public ?float $origin_lng,
        public string $destination_address,
        public ?float $destination_lat,
        public ?float $destination_lng,
        public RideStatus $status,
        public ?float $price,
        public DateData $created_at,
        public DateData $updated_at,
        public ?DateData $cancelled_at = null,
        public ?ActorType $cancelled_by_type = null,
        public ?string $cancelled_by_id = null,
        public ?string $cancelled_reason = null,
    ) {}

    public static function fromModel(Ride $ride): self
    {
        return new self(
            id: $ride->id,
            rider_id: $ride->rider_id,
            driver_id: $ride->driver_id,
            origin_address: $ride->origin_address,
            origin_lat: $ride->origin_lat,
            origin_lng: $ride->origin_lng,
            destination_address: $ride->destination_address,
            destination_lat: $ride->destination_lat,
            destination_lng: $ride->destination_lng,
            status: $ride->status,
            price: $ride->price,
            created_at: DateData::fromCarbon($ride->created_at),
            updated_at: DateData::fromCarbon($ride->updated_at),
            cancelled_at: $ride->cancelled_at ? DateData::fromCarbon($ride->cancelled_at) : null,
            cancelled_by_type: $ride->cancelled_by_type,
            cancelled_by_id: $ride->cancelled_by_id,
            cancelled_reason: $ride->cancelled_reason,
        );
    }
}
