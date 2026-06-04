<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\RebookRideAction;
use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\RebookRideRequest;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Endpoint('Rebook Ride', 'Rebook a previous ride with the same origin and destination')]
#[Response(status: 201, description: 'Ride rebooked successfully.')]
#[Response(status: 403, description: 'Not authorized to rebook this ride.')]
#[Response(status: 422, description: 'Ride is not in a rebookable state or user has an active ride.')]
final class RebookRideController extends Controller
{
    public function __construct(
        private readonly RebookRideAction $rebookRide,
    ) {}

    public function __invoke(RebookRideRequest $request, Ride $ride): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $newRide = $this->rebookRide->handle($user, $ride);

        return ApiResponse::created(
            data: RideData::fromModel($newRide),
            message: __('messages.ride.rebooked'),
        );
    }
}
