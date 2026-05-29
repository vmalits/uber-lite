<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\RideTip;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetRiderTipHistoryQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, RideTip>
     */
    public function execute(
        User $rider,
        int $perPage = 15,
        ?string $from = null,
        ?string $to = null,
    ): LengthAwarePaginator;
}
