<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\RevenueAnalyticsData;

interface GetRevenueAnalyticsQueryInterface
{
    public function execute(int $days): RevenueAnalyticsData;
}
