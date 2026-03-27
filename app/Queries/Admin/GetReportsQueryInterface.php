<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\Report;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetReportsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Report>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
