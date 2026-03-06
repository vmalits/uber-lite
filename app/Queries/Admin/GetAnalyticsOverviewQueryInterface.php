<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\AnalyticsOverviewData;

interface GetAnalyticsOverviewQueryInterface
{
    public function execute(): AnalyticsOverviewData;
}
