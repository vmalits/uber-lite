<?php

declare(strict_types=1);

namespace App\Data\Report;

use App\Data\DateData;
use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Models\Report;
use Spatie\LaravelData\Data;

final class ReportData extends Data
{
    public function __construct(
        public string $id,
        public string $reporter_id,
        public string $target_id,
        public ?string $ride_id,
        public ReportReason $reason,
        public ?string $description,
        public ReportStatus $status,
        public ?string $admin_note,
        public ?string $resolved_by,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(Report $report): self
    {
        return new self(
            id: $report->id,
            reporter_id: $report->reporter_id,
            target_id: $report->target_id,
            ride_id: $report->ride_id,
            reason: $report->reason,
            description: $report->description,
            status: $report->status,
            admin_note: $report->admin_note,
            resolved_by: $report->resolved_by,
            created_at: DateData::fromCarbon($report->created_at),
            updated_at: DateData::fromCarbon($report->updated_at),
        );
    }
}
