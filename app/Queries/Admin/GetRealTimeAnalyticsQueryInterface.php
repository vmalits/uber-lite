<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Dto\Admin\RealTimeAnalyticsData;

interface GetRealTimeAnalyticsQueryInterface
{
    public function execute(): RealTimeAnalyticsData;
}
