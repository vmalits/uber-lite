<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Rider\GetRideHistoryQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @group Rider
 *
 * Get Ride History
 *
 * Retrieve the paginated list of all rides for the authenticated rider.
 *
 * Requires Bearer token and completed profile.
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
 *         "driver_id": "01jk9v6v9v6v9v6v9v6v9v6v9v",
 *         "origin_address": "bd. Ștefan cel Mare și Sfânt, 1, Chișinău",
 *         "origin_lat": 47.0105,
 *         "origin_lng": 28.8638,
 *         "destination_address": "str. Mihai Eminescu, 50, Chișinău",
 *         "destination_lat": 47.0225,
 *         "destination_lng": 28.8353,
 *         "status": "completed",
 *         "price": 50.0,
 *         "created_at": "2025-12-19T20:00:12+00:00"
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
final class GetRideHistoryController extends Controller
{
    public function __construct(
        private readonly GetRideHistoryQueryInterface $getRideHistoryQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Ride::class);

        /** @var User $user */
        $user = $request->user();

        $rides = $this->getRideHistoryQuery->execute(
            $user,
            $request->integer('per_page', 15),
        );

        $rides->through(
            fn (Ride $ride): RideData => RideData::fromModel($ride),
        );

        /** @var LengthAwarePaginator<int, mixed> $rides */
        return ApiResponse::success($rides);
    }
}
