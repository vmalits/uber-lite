<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\WeeklyEarningsData;
use App\Models\User;

interface GetWeeklyEarningsQueryInterface
{
    public function execute(User $user, int $weeks = 4): WeeklyEarningsData;
}
