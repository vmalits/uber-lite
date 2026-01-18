<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetUsersQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
