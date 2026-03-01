<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\MonthlyEarningsData;
use App\Models\User;

interface GetMonthlyEarningsQueryInterface
{
    public function execute(User $user, int $months = 3): MonthlyEarningsData;
}
