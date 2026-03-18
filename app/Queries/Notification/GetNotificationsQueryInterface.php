<?php

declare(strict_types=1);

namespace App\Queries\Notification;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetNotificationsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, mixed>
     */
    public function execute(User $user, int $perPage, bool $unreadOnly = false): LengthAwarePaginator;
}
