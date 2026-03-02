<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\RideTip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetTipHistoryQuery implements GetTipHistoryQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, RideTip>
     */
    public function execute(
        User $driver,
        int $perPage = 15,
        ?string $from = null,
        ?string $to = null,
    ): LengthAwarePaginator {
        $fromDate = $from !== null ? Carbon::parse($from) : Carbon::now()->subDays(30);
        $toDate = $to !== null ? Carbon::parse($to) : Carbon::now();

        return RideTip::query()
            ->where('driver_id', $driver->id)
            ->whereBetween('created_at', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
