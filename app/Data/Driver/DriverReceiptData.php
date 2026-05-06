<?php

declare(strict_types=1);

namespace App\Data\Driver;

use App\Data\DateData;
use App\Data\Rider\RideRatingData;
use App\Data\Rider\TipData;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Services\Avatar\AvatarUrlResolver;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class DriverReceiptData extends Data
{
    public function __construct(
        public string $id,
        public RideStatus $status,
        #[MapName('origin_address')]
        public string $originAddress,
        #[MapName('destination_address')]
        public string $destinationAddress,
        #[MapName('ride_earnings')]
        public int $rideEarnings,
        public ?TipData $tip,
        public ?RideRatingData $rating,
        #[MapName('estimated_distance_km')]
        public ?float $estimatedDistanceKm,
        #[MapName('estimated_duration_min')]
        public ?float $estimatedDurationMin,
        #[MapName('rider_first_name')]
        public ?string $riderFirstName,
        #[MapName('rider_last_name')]
        public ?string $riderLastName,
        #[MapName('rider_avatar_url')]
        public ?string $riderAvatarUrl,
        #[MapName('completed_at')]
        public ?DateData $completedAt,
        #[MapName('created_at')]
        public DateData $createdAt,
    ) {}

    public static function fromModel(Ride $ride, AvatarUrlResolver $avatarResolver): self
    {
        $tip = $ride->relationLoaded('tip') && $ride->tip !== null
            ? new TipData(
                amount: $ride->tip->amount,
                comment: $ride->tip->comment,
            )
            : null;

        $rating = $ride->relationLoaded('rating') && $ride->rating !== null
            ? RideRatingData::fromModel($ride->rating)
            : null;

        $rider = $ride->relationLoaded('rider') ? $ride->rider : null;

        return new self(
            id: $ride->id,
            status: $ride->status,
            originAddress: $ride->origin_address,
            destinationAddress: $ride->destination_address,
            rideEarnings: $ride->price ?? 0,
            tip: $tip,
            rating: $rating,
            estimatedDistanceKm: $ride->estimated_distance_km,
            estimatedDurationMin: $ride->estimated_duration_min,
            riderFirstName: $rider?->first_name,
            riderLastName: $rider?->last_name,
            riderAvatarUrl: $rider !== null ? $avatarResolver->getAllUrls($rider)['thumbnail'] ?? null : null,
            completedAt: $ride->completed_at ? DateData::fromCarbon($ride->completed_at) : null,
            createdAt: DateData::fromCarbon($ride->created_at),
        );
    }
}
