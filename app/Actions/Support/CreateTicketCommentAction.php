<?php

declare(strict_types=1);

namespace App\Actions\Support;

use App\Data\Support\CreateTicketCommentData;
use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Models\User;
use Throwable;

final readonly class CreateTicketCommentAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, SupportTicket $ticket, CreateTicketCommentData $data): SupportTicketComment
    {
        return SupportTicketComment::query()->create([
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'message'   => $data->message,
        ]);
    }
}
