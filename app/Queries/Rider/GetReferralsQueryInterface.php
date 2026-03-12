<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetReferralsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator;
}
