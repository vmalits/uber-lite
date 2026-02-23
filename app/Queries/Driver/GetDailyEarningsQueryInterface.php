<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DailyEarningData;
use App\Models\User;

interface GetDailyEarningsQueryInterface
{
    /**
     * @return array<int, DailyEarningData>
     */
    public function execute(User $user, ?string $from = null, ?string $to = null): array;
}
