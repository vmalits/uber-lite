<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\RideTip;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetTipHistoryQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, RideTip>
     */
    public function execute(
        User $driver,
        int $perPage = 15,
        ?string $from = null,
        ?string $to = null,
    ): LengthAwarePaginator;
}
