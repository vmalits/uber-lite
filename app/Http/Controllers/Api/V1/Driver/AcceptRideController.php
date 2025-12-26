<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\AcceptRide;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * @group Driver
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * Accept a ride request by a driver.
 *
 * @urlParam ride string required The ID of the ride to accept. Example: 01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z
 *
 * @response 200 {
 *   "success": true,
 *   "message": "Ride accepted successfully."
 * }
 */
class AcceptRideController extends Controller
{
    public function __construct(
        private readonly AcceptRide $acceptRide,
    ) {}

    /**
     * @param User $user The authenticated driver.
     * @param Ride $ride The ride to accept.
     *
     * @throws Throwable
     *
     * @return JsonResponse
     */
    public function __invoke(#[CurrentUser] User $user, Ride $ride)
    {
        $this->authorize('accept', $ride);

        $this->acceptRide->handle(
            ride: $ride,
            driverId: $user->id,
        );

        return ApiResponse::success(
            message: 'Ride accepted successfully.',
        );
    }
}
