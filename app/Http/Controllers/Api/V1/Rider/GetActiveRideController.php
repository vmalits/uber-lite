<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Rider\GetActiveRideQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Rider
 *
 * Get Active Ride
 *
 * Retrieve the current active ride for the authenticated rider.
 * Active ride is any ride that is not completed or cancelled.
 *
 * Requires Bearer token and completed profile.
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @response 200 {
 *   "success": true,
 *   "data": {
 *     "id": "01jk9v6v9v6v9v6v9v6v9v6v9v",
 *     "rider_id": "01jk9v6v9v6v9v6v9v6v9v6v9v",
 *     "driver_id": null,
 *     "origin_address": "bd. Ștefan cel Mare și Sfânt, 1, Chișinău",
 *     "origin_lat": 47.0105,
 *     "origin_lng": 28.8638,
 *     "destination_address": "str. Mihai Eminescu, 50, Chișinău",
 *     "destination_lat": 47.0225,
 *     "destination_lng": 28.8353,
 *     "status": "pending",
 *     "price": null,
 *     "created_at": "2025-12-19T20:00:12+00:00"
 *   }
 * }
 * @response 200 {
 *   "success": true,
 *   "message": "No active ride found."
 * }
 * @response 403 {
 *   "success": false,
 *   "message": "Forbidden. Profile step isn't completed."
 * }
 */
final class GetActiveRideController extends Controller
{
    public function __construct(
        private readonly GetActiveRideQueryInterface $getActiveRideQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Ride::class);

        /** @var User $user */
        $user = $request->user();

        $activeRide = $this->getActiveRideQuery->execute($user);

        if ($activeRide === null) {
            return ApiResponse::success(
                data: null,
                message: 'No active ride found.',
            );
        }

        return ApiResponse::success(
            data: RideData::fromModel($activeRide),
        );
    }
}
