<?php

declare(strict_types=1);

namespace App\Data\Ride\Share;

use Spatie\LaravelData\Data;

final class ShareRideData extends Data
{
    public function __construct(
        public string $contact_name,
        public string $contact_phone,
        public ?string $contact_email = null,
        public ?string $message = null,
    ) {}
}
