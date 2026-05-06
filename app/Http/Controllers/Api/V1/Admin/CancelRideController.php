<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\CancelRideAction;
use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\CancelRideRequest;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Cancel Ride', 'Cancel a ride as an admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class CancelRideController extends Controller
{
    public function __construct(
        private readonly CancelRideAction $cancelRide,
    ) {}

    public function __invoke(CancelRideRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('cancelAsAdmin', $ride);

        /** @var array{reason: string} $validated */
        $validated = $request->validated();

        /** @var User $user */
        $user = $request->user();

        try {
            $ride = $this->cancelRide->handle(
                $ride,
                $validated['reason'],
                $user->id,
            );
        } catch (ValidationException $e) {
            return ApiResponse::error(
                message: $e->getMessage(),
                status: 422,
            );
        }

        return ApiResponse::success(
            data: RideData::fromModel($ride->load('rating')),
            message: __('messages.ride.cancelled'),
        );
    }
}
