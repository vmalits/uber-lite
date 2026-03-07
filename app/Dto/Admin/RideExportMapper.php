<?php

declare(strict_types=1);

namespace App\Dto\Admin;

use App\Models\Ride;
use Illuminate\Support\LazyCollection;

final readonly class RideExportMapper
{
    /**
     * @param LazyCollection<int, Ride> $rides
     *
     * @return LazyCollection<int, RideExportRow>
     */
    public function map(LazyCollection $rides): LazyCollection
    {
        return $rides->map(fn (Ride $ride): RideExportRow => RideExportRow::fromModel($ride));
    }
}
