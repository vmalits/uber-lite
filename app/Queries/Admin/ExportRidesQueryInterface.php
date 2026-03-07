<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Dto\Admin\RideExportFilter;
use App\Models\Ride;
use Illuminate\Support\LazyCollection;

interface ExportRidesQueryInterface
{
    /**
     * @return LazyCollection<int, Ride>
     */
    public function execute(RideExportFilter $filter): LazyCollection;
}
