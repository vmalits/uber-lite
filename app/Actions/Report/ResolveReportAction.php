<?php

declare(strict_types=1);

namespace App\Actions\Report;

use App\Data\Report\ResolveReportData;
use App\Enums\ReportStatus;
use App\Models\Report;

final readonly class ResolveReportAction
{
    public function handle(Report $report, ResolveReportData $data, string $adminId): Report
    {
        $report->update([
            'status'      => $data->status,
            'admin_note'  => $data->admin_note,
            'resolved_by' => $data->status !== ReportStatus::PENDING ? $adminId : null,
            'resolved_at' => $data->status !== ReportStatus::PENDING ? now() : null,
        ]);

        return $report->refresh()->load(['reporter', 'target', 'resolver']);
    }
}
