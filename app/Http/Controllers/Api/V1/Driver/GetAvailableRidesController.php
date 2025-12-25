<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Queries\Driver\GetAvailableRidesQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @group Driver
 *
 * Get Available Rides
 *
 * Retrieve a list of rides available to accept.
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @queryParam per_page int Page size. Default: 15. Example: 15
 * @queryParam page int Page number. Example: 1
 *
 * @response 200 {
 *   "success": true,
 *   "data": {
 *     "items": [
 *       {
 *         "id": "01jk9v6v9v6v9v6v9v6v9v6v9v",
 *         "rider_id": "01jk9v6v9v6v9v6v9v6v9v6v9v",
 *         "origin_address": "bd. Ștefan cel Mare și Sfânt, 1, Chișinău",
 *         "origin_lat": 47.0105,
 *         "origin_lng": 28.8638,
 *         "destination_address": "str. Mihai Eminescu, 50, Chișinău",
 *         "destination_lat": 47.0225,
 *         "destination_lng": 28.8353,
 *         "status": "pending"
 *       }
 *     ],
 *     "pagination": {
 *       "total": 1,
 *       "per_page": 15,
 *       "current_page": 1,
 *       "last_page": 1
 *     }
 *   }
 * }
 */
class GetAvailableRidesController extends Controller
{
    public function __construct(
        private readonly GetAvailableRidesQueryInterface $getAvailableRidesQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('view-available', Ride::class);

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, Ride> $rides */
        $rides = $this->getAvailableRidesQuery->execute($perPage);

        $rides->through(
            fn (Ride $ride): RideData => RideData::fromModel($ride),
        );

        /** @var LengthAwarePaginator<int, mixed> $rides */
        return ApiResponse::success($rides);
    }
}
