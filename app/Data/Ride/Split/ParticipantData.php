<?php

declare(strict_types=1);

namespace App\Data\Ride\Split;

use Spatie\LaravelData\Data;

final class ParticipantData extends Data
{
    public function __construct(
        public string $name,
        public ?string $email = null,
        public ?string $phone = null,
        public ?float $share = null,
    ) {}
}
