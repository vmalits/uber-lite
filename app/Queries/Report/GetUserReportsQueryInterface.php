<?php

declare(strict_types=1);

namespace App\Queries\Report;

use App\Models\Report;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetUserReportsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Report>
     */
    public function execute(string $userId, int $perPage): LengthAwarePaginator;
}
