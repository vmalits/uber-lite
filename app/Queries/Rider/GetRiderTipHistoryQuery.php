<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\RideTip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetRiderTipHistoryQuery implements GetRiderTipHistoryQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, RideTip>
     */
    public function execute(
        User $rider,
        int $perPage = 15,
        ?string $from = null,
        ?string $to = null,
    ): LengthAwarePaginator {
        $fromDate = $from !== null ? Carbon::parse($from) : Carbon::now()->subDays(30);
        $toDate = $to !== null ? Carbon::parse($to) : Carbon::now();

        return RideTip::query()
            ->where('rider_id', $rider->id)
            ->whereBetween('created_at', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
