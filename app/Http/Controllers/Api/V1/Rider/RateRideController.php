<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\RateRideAction;
use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\RateRideRequest;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ride',
    type: 'string',
    description: 'ULID of the ride.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v',
)]
#[ResponseFromFile('docs/examples/rate_ride.json', status: 200)]
#[Response(status: 404, description: 'Ride not found.')]
#[Response(status: 403, description: 'Forbidden - You can only rate your own completed rides.')]
#[Response(status: 422, description: 'Validation failed or rating update restricted (e.g., within 24 hours).')]
final class RateRideController extends Controller
{
    public function __construct(
        private readonly RateRideAction $rateRide,
    ) {}

    public function __invoke(RateRideRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('rate', $ride);

        $rateRideData = $request->toRateRideData();

        /** @var User $rider */
        $rider = $request->user();

        $ride = $this->rateRide->handle($ride, $rateRideData, $rider);

        return ApiResponse::success(
            data: RideData::fromModel($ride),
            message: __('messages.ride.rated'),
        );
    }
}
