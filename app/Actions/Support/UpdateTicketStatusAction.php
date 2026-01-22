<?php

declare(strict_types=1);

namespace App\Actions\Support;

use App\Data\Support\UpdateTicketStatusData;
use App\Models\SupportTicket;

final readonly class UpdateTicketStatusAction
{
    public function handle(SupportTicket $ticket, UpdateTicketStatusData $data): SupportTicket
    {
        $ticket->update([
            'status' => $data->status,
        ]);

        return $ticket;
    }
}
