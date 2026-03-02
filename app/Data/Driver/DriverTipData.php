<?php

declare(strict_types=1);

namespace App\Data\Driver;

use App\Data\DateData;
use App\Models\RideTip;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class DriverTipData extends Data
{
    public function __construct(
        public string $id,
        #[MapName('ride_id')]
        public string $rideId,
        public int $amount,
        public ?string $comment,
        public DateData $created_at,
    ) {}

    public static function fromModel(RideTip $tip): self
    {
        return new self(
            id: $tip->id,
            rideId: $tip->ride_id,
            amount: $tip->amount,
            comment: $tip->comment,
            created_at: DateData::fromCarbon($tip->created_at),
        );
    }
}
