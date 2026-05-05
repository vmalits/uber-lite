<?php

declare(strict_types=1);

namespace App\Queries\Report;

use App\Models\Report;

interface GetUserReportQueryInterface
{
    public function execute(string $reportId, string $userId): Report;
}
