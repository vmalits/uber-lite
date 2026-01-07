<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Models\RideRating;
use Spatie\LaravelData\Data;

final class RideRatingData extends Data
{
    public function __construct(
        public int $rating,
        public ?string $comment,
        public DateData $created_at,
    ) {}

    public static function fromModel(RideRating $rating): self
    {
        return new self(
            rating: $rating->rating,
            comment: $rating->comment,
            created_at: DateData::fromCarbon($rating->created_at),
        );
    }
}
