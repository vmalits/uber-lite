<?php

declare(strict_types=1);

namespace App\Data\Support;

use Spatie\LaravelData\Data;

final class CreateTicketCommentData extends Data
{
    public function __construct(
        public string $message,
    ) {}
}
