<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\SupportTicket;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetTicketsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, SupportTicket>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
