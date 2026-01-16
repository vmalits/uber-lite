<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\CancelRideAction;
use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ride',
    type: 'string',
    description: 'ULID of the ride.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v',
)]
#[Response(status: 200, description: 'Ride cancelled successfully.')]
#[Response(status: 404, description: 'Ride not found.')]
#[Response(status: 422, description: 'Ride cannot be cancelled in its current status.')]
final class CancelRideController extends Controller
{
    public function __construct(
        private readonly CancelRideAction $cancelRide,
    ) {}

    public function __invoke(Ride $ride): JsonResponse
    {
        $this->authorize('cancel', $ride);

        $ride = $this->cancelRide->handle($ride);

        return ApiResponse::success(
            data: RideData::fromModel($ride),
            message: __('messages.ride.cancelled'),
        );
    }
}
