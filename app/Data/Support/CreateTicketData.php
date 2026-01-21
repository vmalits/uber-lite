<?php

declare(strict_types=1);

namespace App\Data\Support;

use Spatie\LaravelData\Data;

final class CreateTicketData extends Data
{
    public function __construct(
        public string $subject,
        public string $message,
    ) {}
}
