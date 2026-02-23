<?php

declare(strict_types=1);

namespace App\Data\Ride\Split;

use App\Models\RideSplit;
use Spatie\LaravelData\Data;

final class RideSplitData extends Data
{
    public function __construct(
        public string $id,
        public string $participant_name,
        public ?string $participant_email,
        public ?string $participant_phone,
        public ?float $share,
    ) {}

    public static function fromModel(RideSplit $split): self
    {
        return new self(
            id: $split->id,
            participant_name: $split->participant_name,
            participant_email: $split->participant_email,
            participant_phone: $split->participant_phone,
            share: $split->share !== null ? (float) $split->share : null,
        );
    }
}
