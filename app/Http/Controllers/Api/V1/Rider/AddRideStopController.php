<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\AddRideStopAction;
use App\Data\Rider\RideStopData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\AddRideStopRequest;
use App\Models\Ride;
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
#[ResponseFromFile('docs/examples/add_ride_stop.json', status: 201)]
#[Response(status: 403, description: 'Forbidden - You can only add stops to your own rides.')]
#[Response(status: 422, description: 'Maximum stops reached or ride already in progress.')]
final class AddRideStopController extends Controller
{
    public function __construct(
        private readonly AddRideStopAction $action,
    ) {}

    public function __invoke(AddRideStopRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('addStop', $ride);

        $stop = $this->action->handle(
            ride: $ride,
            data: $request->toAddRideStopData(),
        );

        return ApiResponse::success(
            data: RideStopData::fromModel($stop),
            message: __('messages.ride.stop_added'),
            status: 201,
        );
    }
}
