<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\ScheduleRideAction;
use App\Data\Rider\RideData;
use App\Data\Rider\ScheduleRideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\ScheduleRideRequest;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Ride scheduled successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
#[Response(status: 403, description: 'Profile not completed.')]
final class ScheduleRideController extends Controller
{
    public function __construct(
        private readonly ScheduleRideAction $scheduleRide,
    ) {}

    public function __invoke(ScheduleRideRequest $request): JsonResponse
    {
        $this->authorize('create', Ride::class);

        /** @var User $user */
        $user = $request->user();

        $ride = $this->scheduleRide->handle(
            $user,
            ScheduleRideData::from($request->validated()),
        );

        return ApiResponse::created(
            data: RideData::fromModel($ride),
            message: __('messages.ride.scheduled'),
        );
    }
}
