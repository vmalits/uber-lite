<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Enums\PayoutStatus;
use App\Models\DriverPayout;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetPayoutHistoryQuery implements GetPayoutHistoryQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, DriverPayout>
     */
    public function execute(
        User $driver,
        int $perPage = 15,
        ?string $from = null,
        ?string $to = null,
        ?string $status = null,
    ): LengthAwarePaginator {
        $fromDate = $from !== null ? Carbon::parse($from) : Carbon::now()->subDays(30);
        $toDate = $to !== null ? Carbon::parse($to) : Carbon::now();

        $query = DriverPayout::query()
            ->where('driver_id', $driver->id)
            ->whereBetween('created_at', [$fromDate->startOfDay(), $toDate->endOfDay()]);

        if ($status !== null) {
            $query->where('status', PayoutStatus::from($status));
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
