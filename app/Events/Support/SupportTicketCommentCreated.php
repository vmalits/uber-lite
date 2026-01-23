<?php

declare(strict_types=1);

namespace App\Events\Support;

use App\Data\Support\SupportTicketCommentData;
use App\Models\SupportTicketComment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class SupportTicketCommentCreated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly SupportTicketComment $comment,
    ) {}

    public function broadcastOn(): array
    {
        $this->comment->loadMissing('ticket.user');

        /** @var User $owner */
        $owner = $this->comment->ticket->user;
        $role = $owner->role?->value;

        return [
            new Channel("{$role}:{$owner->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'support.ticket_comment_created';
    }

    /**
     * @return array{ticket_id: string, comment: array<string, mixed>}
     */
    public function broadcastWith(): array
    {
        $this->comment->loadMissing('user');

        /** @var array<string, mixed> $commentData */
        $commentData = SupportTicketCommentData::fromModel($this->comment)->toArray();

        return [
            'ticket_id' => $this->comment->ticket_id,
            'comment'   => $commentData,
        ];
    }
}
