<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Models\RideStop;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param int $order
 * @param string $address
 * @param float|null $lat
 * @param float|null $lng
 * @param DateData $created_at
 */
final class RideStopData extends Data
{
    public function __construct(
        public string $id,
        public int $order,
        public string $address,
        public ?float $lat,
        public ?float $lng,
        public DateData $created_at,
    ) {}

    public static function fromModel(RideStop $stop): self
    {
        return new self(
            id: $stop->id,
            order: $stop->order,
            address: $stop->address,
            lat: $stop->lat,
            lng: $stop->lng,
            created_at: DateData::fromCarbon($stop->created_at),
        );
    }
}
