<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\Report;

interface GetReportQueryInterface
{
    public function execute(string $id): Report;
}
