<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\RidesAnalyticsData;

interface GetRidesAnalyticsQueryInterface
{
    public function execute(int $days): RidesAnalyticsData;
}
