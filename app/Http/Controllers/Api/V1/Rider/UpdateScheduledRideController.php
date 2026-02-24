<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\UpdateScheduledRideAction;
use App\Data\Rider\RideData;
use App\Data\Rider\UpdateScheduledRideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\UpdateScheduledRideRequest;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Scheduled ride updated successfully.')]
#[Response(status: 403, description: 'Cannot update this ride.')]
#[Response(status: 404, description: 'Ride not found.')]
#[Response(status: 422, description: 'Validation errors.')]
final class UpdateScheduledRideController extends Controller
{
    public function __construct(
        private readonly UpdateScheduledRideAction $updateScheduledRide,
    ) {}

    public function __invoke(UpdateScheduledRideRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('updateScheduled', $ride);

        $ride = $this->updateScheduledRide->handle(
            ride: $ride,
            data: UpdateScheduledRideData::from($request->validated()),
        );

        return ApiResponse::success(
            data: RideData::fromModel($ride),
            message: __('messages.ride.scheduled_updated'),
        );
    }
}
