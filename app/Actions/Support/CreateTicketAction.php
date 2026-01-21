<?php

declare(strict_types=1);

namespace App\Actions\Support;

use App\Data\Support\CreateTicketData;
use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use App\Models\User;
use Throwable;

final readonly class CreateTicketAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, CreateTicketData $data): SupportTicket
    {
        return SupportTicket::query()->create([
            'user_id' => $user->id,
            'subject' => $data->subject,
            'message' => $data->message,
            'status'  => SupportTicketStatus::OPEN,
        ]);
    }
}
