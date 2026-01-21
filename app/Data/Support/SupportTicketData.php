<?php

declare(strict_types=1);

namespace App\Data\Support;

use App\Data\DateData;
use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use Spatie\LaravelData\Data;

final class SupportTicketData extends Data
{
    public function __construct(
        public string $id,
        public string $user_id,
        public string $subject,
        public string $message,
        public SupportTicketStatus $status,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(SupportTicket $ticket): self
    {
        return new self(
            id: $ticket->id,
            user_id: $ticket->user_id,
            subject: $ticket->subject,
            message: $ticket->message,
            status: $ticket->status,
            created_at: DateData::fromCarbon($ticket->created_at),
            updated_at: DateData::fromCarbon($ticket->updated_at),
        );
    }
}
