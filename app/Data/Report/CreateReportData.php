<?php

declare(strict_types=1);

namespace App\Data\Report;

use App\Enums\ReportReason;
use Spatie\LaravelData\Data;

final class CreateReportData extends Data
{
    public function __construct(
        public string $target_id,
        public ReportReason $reason,
        public ?string $description,
        public ?string $ride_id,
    ) {}
}
