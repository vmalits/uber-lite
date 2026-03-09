<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Models\RideRating;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param int $rating
 * @param string|null $comment
 * @param string|null $rider_name
 * @param DateData $created_at
 */
final class DriverReviewData extends Data
{
    public function __construct(
        public string $id,
        public int $rating,
        public ?string $comment,
        public ?string $rider_name,
        public DateData $created_at,
    ) {}

    public static function fromModel(RideRating $rating): self
    {
        $rider = $rating->relationLoaded('rider') ? $rating->rider : null;
        $riderName = $rider !== null
            ? trim(\sprintf('%s %s', $rider->first_name ?? '', $rider->last_name ?? '')) ?: null
            : null;

        return new self(
            id: $rating->id,
            rating: $rating->rating,
            comment: $rating->comment,
            rider_name: $riderName,
            created_at: DateData::fromCarbon($rating->created_at),
        );
    }
}
