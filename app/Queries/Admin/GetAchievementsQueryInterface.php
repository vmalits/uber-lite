<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\Achievement;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetAchievementsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Achievement>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
