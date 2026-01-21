<?php

declare(strict_types=1);

namespace App\Queries\Support;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetTicketsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, SupportTicket>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator;
}
