<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Models\RideTip;
use Spatie\LaravelData\Data;

final class RiderTipData extends Data
{
    public function __construct(
        public string $id,
        public string $ride_id,
        public int $amount,
        public ?string $comment,
        public DateData $created_at,
    ) {}

    public static function fromModel(RideTip $tip): self
    {
        return new self(
            id: $tip->id,
            ride_id: $tip->ride_id,
            amount: $tip->amount,
            comment: $tip->comment,
            created_at: DateData::fromCarbon($tip->created_at),
        );
    }
}
