<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\ReceiptData;
use App\Enums\RideStatus;
use App\Models\Ride;
use App\Services\Avatar\AvatarUrlResolver;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetReceiptsQuery implements GetReceiptsQueryInterface
{
    public function __construct(
        private AvatarUrlResolver $avatarResolver,
    ) {}

    public function execute(
        string $riderId,
        int $perPage = 15,
        ?string $from = null,
        ?string $to = null,
    ): LengthAwarePaginator {
        $fromDate = $from !== null ? Carbon::parse($from) : Carbon::now()->subDays(30);
        $toDate = $to !== null ? Carbon::parse($to) : Carbon::now();

        $rides = Ride::query()
            ->with(['driver', 'tip', 'rating', 'promoCode'])
            ->where('rider_id', $riderId)
            ->where('status', RideStatus::COMPLETED)
            ->whereBetween('completed_at', [$fromDate->startOfDay(), $toDate->endOfDay()])
            ->orderBy('completed_at', 'desc')
            ->paginate($perPage);

        /** @var LengthAwarePaginator<int, ReceiptData> $receipts */
        $receipts = $rides->through(
            fn (Ride $ride): ReceiptData => ReceiptData::fromModel($ride, $this->avatarResolver),
        );

        return $receipts;
    }
}
