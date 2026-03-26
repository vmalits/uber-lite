<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\Announcement;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetAnnouncementsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Announcement>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
