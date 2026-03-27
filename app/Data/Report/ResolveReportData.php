<?php

declare(strict_types=1);

namespace App\Data\Report;

use App\Enums\ReportStatus;
use Spatie\LaravelData\Data;

final class ResolveReportData extends Data
{
    public function __construct(
        public ReportStatus $status,
        public ?string $admin_note,
    ) {}
}
