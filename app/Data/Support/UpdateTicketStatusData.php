<?php

declare(strict_types=1);

namespace App\Data\Support;

use App\Enums\SupportTicketStatus;
use Spatie\LaravelData\Data;

final class UpdateTicketStatusData extends Data
{
    public function __construct(
        public SupportTicketStatus $status,
    ) {}
}
