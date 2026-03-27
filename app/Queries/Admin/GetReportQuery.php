<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\Report;

final readonly class GetReportQuery implements GetReportQueryInterface
{
    public function execute(string $id): Report
    {
        return Report::with(['reporter', 'target', 'ride', 'resolver'])->findOrFail($id);
    }
}
