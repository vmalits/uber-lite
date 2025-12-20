<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\CancelRide;
use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Rider
 *
 * Cancel Ride
 *
 * Cancel a ride request. Can only be done by the rider who created it
 * and only if the ride is not already completed or cancelled.
 *
 * Requires Bearer token and completed profile.
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @urlParam id string required The ULID of the ride. Example: 01jk9v6v9v6v9v6v9v6v9v6v9v
 *
 * @response 200 {
 *   "success": true,
 *   "message": "Ride cancelled successfully.",
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
 *     "status": "cancelled",
 *     "price": null,
 *     "created_at": "2025-12-19T20:00:12+00:00"
 *   }
 * }
 * @response 422 {
 *   "success": false,
 *   "message": "Ride cannot be cancelled in its current status.",
 *   "errors": {
 *     "ride": ["Ride cannot be cancelled in its current status."]
 *   }
 * }
 * @response 404 {
 *   "success": false,
 *   "message": "Ride not found."
 * }
 * @response 403 {
 *   "success": false,
 *   "message": "Forbidden. Profile step isn't completed."
 * }
 */
final class CancelRideController extends Controller
{
    public function __construct(
        private readonly CancelRide $cancelRide,
    ) {}

    public function __invoke(Request $request, Ride $ride): JsonResponse
    {
        $this->authorize('cancel', $ride);

        $this->cancelRide->handle($ride);

        return ApiResponse::success(
            data: RideData::fromModel($ride),
            message: 'Ride cancelled successfully.',
        );
    }
}
