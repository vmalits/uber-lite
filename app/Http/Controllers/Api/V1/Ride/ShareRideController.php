<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ride;

use App\Actions\Ride\ShareRideAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Ride\ShareRideRequest;
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
#[Response(status: 201, description: 'Ride shared successfully.')]
#[Response(status: 401, description: 'Unauthenticated.')]
#[Response(status: 403, description: 'Forbidden - You can only share your own rides.')]
#[Response(status: 404, description: 'Ride not found.')]
#[Response(status: 422, description: 'Validation failed.')]
final class ShareRideController extends Controller
{
    public function __construct(
        private readonly ShareRideAction $shareRide,
    ) {}

    public function __invoke(ShareRideRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('share', $ride);

        $shareRideData = $request->toData();

        $this->shareRide->handle($ride, $shareRideData);

        return ApiResponse::created(
            data: [
                'ride_id' => $ride->id,
            ],
            message: __('messages.ride.shared'),
        );
    }
}
