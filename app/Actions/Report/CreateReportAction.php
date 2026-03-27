<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Data\Report\CreateReportData;
use App\Enums\ReportStatus;
use App\Models\Report;

final readonly class CreateReportAction
{
    public function handle(CreateReportData $data, string $reporterId): Report
    {
        return Report::query()->create([
            'reporter_id' => $reporterId,
            'target_id'   => $data->target_id,
            'ride_id'     => $data->ride_id,
            'reason'      => $data->reason,
            'description' => $data->description,
            'status'      => ReportStatus::PENDING,
        ]);
    }
}
