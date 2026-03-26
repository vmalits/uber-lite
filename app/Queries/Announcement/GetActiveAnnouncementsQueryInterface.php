<?php

declare(strict_types=1);

namespace App\Queries\Announcement;

use App\Enums\UserRole;
use App\Models\Announcement;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetActiveAnnouncementsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Announcement>
     */
    public function execute(UserRole $role, int $perPage): LengthAwarePaginator;
}
