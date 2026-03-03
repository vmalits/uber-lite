<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\DriverPayout;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetPayoutHistoryQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, DriverPayout>
     */
    public function execute(
        User $driver,
        int $perPage = 15,
        ?string $from = null,
        ?string $to = null,
        ?string $status = null,
    ): LengthAwarePaginator;
}
