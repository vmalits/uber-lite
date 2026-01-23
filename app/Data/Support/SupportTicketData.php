<?php

declare(strict_types=1);

namespace App\Data\Support;

use App\Data\DateData;
use App\Data\User\UserData;
use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use App\Services\Avatar\AvatarUrlResolver;
use Spatie\LaravelData\Data;

final class SupportTicketData extends Data
{
    public function __construct(
        public string $id,
        public string $user_id,
        public string $subject,
        public string $message,
        public SupportTicketStatus $status,
        public UserData $user,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(SupportTicket $ticket): self
    {
        /** @var AvatarUrlResolver $avatarResolver */
        $avatarResolver = app(AvatarUrlResolver::class);

        return new self(
            id: $ticket->id,
            user_id: $ticket->user_id,
            subject: $ticket->subject,
            message: $ticket->message,
            status: $ticket->status,
            user: UserData::fromModel($ticket->user, $avatarResolver),
            created_at: DateData::fromCarbon($ticket->created_at),
            updated_at: DateData::fromCarbon($ticket->updated_at),
        );
    }
}
