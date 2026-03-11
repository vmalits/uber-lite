<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\GetPayoutsData;
use App\Enums\PayoutStatus;
use App\Models\DriverPayout;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetPayoutsQuery implements GetPayoutsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, DriverPayout>
     */
    public function execute(GetPayoutsData $data): LengthAwarePaginator
    {
        $query = DriverPayout::query()
            ->with('driver');

        if ($data->status !== null) {
            $query->where('status', PayoutStatus::from($data->status));
        }

        if ($data->driverId !== null) {
            $query->where('driver_id', $data->driverId);
        }

        if ($data->from !== null || $data->to !== null) {
            $fromDate = $data->from !== null ? Carbon::parse($data->from) : Carbon::now()->subDays(30);
            $toDate = $data->to !== null ? Carbon::parse($data->to) : Carbon::now();
            $query->whereBetween('created_at', [$fromDate->startOfDay(), $toDate->endOfDay()]);
        }

        return $query->orderBy('created_at', 'desc')->paginate($data->perPage);
    }
}
