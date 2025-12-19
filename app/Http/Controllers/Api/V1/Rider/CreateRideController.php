<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\CreateRide;
use App\Data\Rider\CreateRideData;
use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\CreateRideRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * @group Rider
 *
 * Create Ride
 *
 * Endpoint for riders to create a new ride request.
 *
 * Requires Bearer token and completed profile.
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @response 201 {
 *   "success": true,
 *   "message": "Ride created successfully.",
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
 * @response 422 {
 *   "success": false,
 *   "message": "The given data was invalid.",
 *   "errors": {
 *     "origin_address": ["The origin address field is required."]
 *   }
 * }
 * @response 403 {
 *   "success": false,
 *   "message": "Forbidden. Profile step isn't completed."
 * }
 */
final class CreateRideController extends Controller
{
    public function __construct(
        private readonly CreateRide $createRide,
    ) {}

    public function __invoke(CreateRideRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $ride = $this->createRide->handle(
            $user,
            CreateRideData::from($request->validated()),
        );

        return ApiResponse::created(
            data: RideData::fromModel($ride),
            message: 'Ride created successfully.',
        );
    }
}
