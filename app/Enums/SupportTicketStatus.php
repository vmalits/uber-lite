<?php

declare(strict_types=1);

namespace App\Enums;

enum SupportTicketStatus: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case PENDING = 'pending';
}
