<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\ScheduleData;
use App\Models\User;
use Illuminate\Support\Collection;

interface GetScheduleQueryInterface
{
    /**
     * @return Collection<int, ScheduleData>
     */
    public function execute(User $driver): Collection;
}
