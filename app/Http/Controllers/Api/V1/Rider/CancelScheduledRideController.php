<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\CancelScheduledRideAction;
use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Cancel Scheduled Ride', 'Cancel a scheduled ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Scheduled ride cancelled successfully.')]
#[Response(status: 403, description: 'Cannot cancel this ride.')]
#[Response(status: 404, description: 'Ride not found.')]
final class CancelScheduledRideController extends Controller
{
    public function __construct(
        private readonly CancelScheduledRideAction $cancelScheduledRide,
    ) {}

    public function __invoke(Request $request, Ride $ride): JsonResponse
    {
        $this->authorize('cancelScheduled', $ride);

        /** @var User $user */
        $user = $request->user();

        $ride = $this->cancelScheduledRide->handle($user, $ride);

        return ApiResponse::success(
            data: RideData::fromModel($ride),
            message: __('messages.ride.scheduled_cancelled'),
        );
    }
}
