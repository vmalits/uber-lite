<?php

declare(strict_types=1);

namespace App\Data\Support;

use App\Data\DateData;
use App\Data\User\UserData;
use App\Models\SupportTicketComment;
use App\Services\Avatar\AvatarUrlResolver;
use Spatie\LaravelData\Data;

final class SupportTicketCommentData extends Data
{
    public function __construct(
        public string $id,
        public string $ticket_id,
        public string $user_id,
        public string $message,
        public UserData $user,
        public DateData $created_at,
    ) {}

    public static function fromModel(SupportTicketComment $comment): self
    {
        /** @var AvatarUrlResolver $avatarResolver */
        $avatarResolver = app(AvatarUrlResolver::class);

        return new self(
            id: $comment->id,
            ticket_id: $comment->ticket_id,
            user_id: $comment->user_id,
            message: $comment->message,
            user: UserData::fromModel($comment->user, $avatarResolver),
            created_at: DateData::fromCarbon($comment->created_at),
        );
    }
}
