<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use Illuminate\Support\Collection;

interface GetHeatmapQueryInterface
{
    /**
     * @return Collection<int, \App\Data\Driver\HeatmapPointData>
     */
    public function execute(): Collection;
}
