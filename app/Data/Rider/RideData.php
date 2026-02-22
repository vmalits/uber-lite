<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\RideRating;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param string $rider_id
 * @param string|null $driver_id
 * @param string $origin_address
 * @param float|null $origin_lat
 * @param float|null $origin_lng
 * @param string $destination_address
 * @param float|null $destination_lat
 * @param float|null $destination_lng
 * @param RideStatus $status
 * @param float|null $price
 * @param float|null $estimated_price
 * @param float|null $estimated_distance_km
 * @param float|null $estimated_duration_min
 * @param float|null $price_per_km
 * @param float|null $price_per_minute
 * @param float|null $base_fee
 * @param DateData|null $arrived_at
 * @param DateData|null $started_at
 * @param DateData|null $cancelled_at
 * @param ActorType|null $cancelled_by_type
 * @param string|null $cancelled_by_id
 * @param string|null $cancelled_reason
 * @param ?DateData $completed_at
 * @param ?DateData $scheduled_at
 * @param RideRatingData|null $rating
 * @param DateData $created_at
 * @param DateData $updated_at
 */
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
        public ?float $estimated_price,
        public ?float $estimated_distance_km,
        public ?float $estimated_duration_min,
        public ?float $price_per_km,
        public ?float $price_per_minute,
        public ?float $base_fee,
        public ?DateData $arrived_at,
        public ?DateData $started_at,
        public ?DateData $cancelled_at,
        public ?ActorType $cancelled_by_type,
        public ?string $cancelled_by_id,
        public ?string $cancelled_reason,
        public ?DateData $completed_at,
        public ?DateData $scheduled_at,
        public ?RideRatingData $rating,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(Ride $ride): self
    {
        $rating = $ride->relationLoaded('rating') && $ride->rating instanceof RideRating
            ? $ride->rating
            : null;

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
            estimated_price: $ride->estimated_price,
            estimated_distance_km: $ride->estimated_distance_km,
            estimated_duration_min: $ride->estimated_duration_min,
            price_per_km: $ride->price_per_km,
            price_per_minute: $ride->price_per_minute,
            base_fee: $ride->base_fee,
            arrived_at: $ride->arrived_at ? DateData::fromCarbon($ride->arrived_at) : null,
            started_at: $ride->started_at ? DateData::fromCarbon($ride->started_at) : null,
            cancelled_at: $ride->cancelled_at ? DateData::fromCarbon($ride->cancelled_at) : null,
            cancelled_by_type: $ride->cancelled_by_type,
            cancelled_by_id: $ride->cancelled_by_id,
            cancelled_reason: $ride->cancelled_reason,
            completed_at: $ride->completed_at ? DateData::fromCarbon($ride->completed_at) : null,
            scheduled_at: $ride->scheduled_at ? DateData::fromCarbon($ride->scheduled_at) : null,
            rating: $rating ? RideRatingData::fromModel($rating) : null,
            created_at: DateData::fromCarbon($ride->created_at),
            updated_at: DateData::fromCarbon($ride->updated_at),
        );
    }
}
