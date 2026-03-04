<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Services\Avatar\AvatarUrlResolver;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class ReceiptData extends Data
{
    public function __construct(
        public string $id,
        public RideStatus $status,
        #[MapName('origin_address')]
        public string $originAddress,
        #[MapName('destination_address')]
        public string $destinationAddress,
        public int $price,
        #[MapName('discount_amount')]
        public ?int $discountAmount,
        #[MapName('promo_code')]
        public ?string $promoCode,
        #[MapName('base_fee')]
        public ?float $baseFee,
        #[MapName('price_per_km')]
        public ?float $pricePerKm,
        #[MapName('price_per_minute')]
        public ?float $pricePerMinute,
        #[MapName('estimated_distance_km')]
        public ?float $estimatedDistanceKm,
        #[MapName('estimated_duration_min')]
        public ?float $estimatedDurationMin,
        public ?TipData $tip,
        public ?RideRatingData $rating,
        #[MapName('driver_first_name')]
        public ?string $driverFirstName,
        #[MapName('driver_last_name')]
        public ?string $driverLastName,
        #[MapName('driver_avatar_url')]
        public ?string $driverAvatarUrl,
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

        $driver = $ride->relationLoaded('driver') ? $ride->driver : null;

        return new self(
            id: $ride->id,
            status: $ride->status,
            originAddress: $ride->origin_address,
            destinationAddress: $ride->destination_address,
            price: $ride->price ?? 0,
            discountAmount: $ride->discount_amount,
            promoCode: $ride->relationLoaded('promoCode') ? $ride->promoCode?->code : null,
            baseFee: $ride->base_fee,
            pricePerKm: $ride->price_per_km,
            pricePerMinute: $ride->price_per_minute,
            estimatedDistanceKm: $ride->estimated_distance_km,
            estimatedDurationMin: $ride->estimated_duration_min,
            tip: $tip,
            rating: $rating,
            driverFirstName: $driver?->first_name,
            driverLastName: $driver?->last_name,
            driverAvatarUrl: $driver !== null ? $avatarResolver->getAllUrls($driver)['thumbnail'] ?? null : null,
            completedAt: $ride->completed_at ? DateData::fromCarbon($ride->completed_at) : null,
            createdAt: DateData::fromCarbon($ride->created_at),
        );
    }
}
