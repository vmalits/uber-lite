<?php

declare(strict_types=1);

namespace App\Data\Ride\Split;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

/**
 * @property DataCollection<int, ParticipantData> $participants
 */
final class SplitRideData extends Data
{
    /**
     * @param DataCollection<int, ParticipantData> $participants
     */
    public function __construct(
        #[DataCollectionOf(ParticipantData::class)]
        public DataCollection $participants,
        public ?string $note = null,
    ) {}
}
