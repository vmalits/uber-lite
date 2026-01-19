<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetDriversQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
