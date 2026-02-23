<?php

declare(strict_types=1);

namespace App\Data\Ride\Split;

use Spatie\LaravelData\Data;

final class SplitRideResponseData extends Data
{
    /**
     * @param array<int, array{name: string, email: string|null, phone: string|null, share: float|null, track_url: string}> $invitations
     */
    public function __construct(
        public string $ride_id,
        public array $invitations,
    ) {}
}
