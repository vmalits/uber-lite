<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ride;

use App\Actions\Ride\SplitRideAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Ride\SplitRideRequest;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ride',
    type: 'string',
    description: 'ULID of the ride.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v',
)]
#[Response(status: 201, description: 'Ride split created successfully.')]
#[Response(status: 401, description: 'Unauthenticated.')]
#[Response(status: 403, description: 'Forbidden - You can only split your own rides.')]
#[Response(status: 404, description: 'Ride not found.')]
#[Response(status: 422, description: 'Validation failed.')]
final class SplitRideController extends Controller
{
    public function __construct(
        private readonly SplitRideAction $splitRide,
    ) {}

    public function __invoke(SplitRideRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('split', $ride);

        $response = $this->splitRide->handle($ride, $request->toData());

        return ApiResponse::created(
            data: $response,
            message: __('messages.ride.split'),
        );
    }
}
