<?php

declare(strict_types=1);

namespace App\Queries\Support;

use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use Illuminate\Support\Collection;

final readonly class GetTicketCommentsQuery implements GetTicketCommentsQueryInterface
{
    /**
     * @return Collection<int, SupportTicketComment>
     */
    public function execute(SupportTicket $ticket): Collection
    {
        return $ticket->comments()
            ->with(['user'])
            ->oldest()
            ->get();
    }
}
