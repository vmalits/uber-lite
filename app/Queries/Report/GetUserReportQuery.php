<?php

declare(strict_types=1);

namespace App\Queries\Report;

use App\Models\Report;

final readonly class GetUserReportQuery implements GetUserReportQueryInterface
{
    public function execute(string $reportId, string $userId): Report
    {
        return Report::query()
            ->where('id', $reportId)
            ->where('reporter_id', $userId)
            ->firstOrFail();
    }
}
