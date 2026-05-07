<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\DeleteRideStopAction;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\RideStop;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Delete Ride Stop', 'Remove an intermediate stop from a ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Stop deleted successfully.')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Stop not found')]
final class DeleteRideStopController extends Controller
{
    public function __construct(
        private readonly DeleteRideStopAction $action,
    ) {}

    public function __invoke(Ride $ride, RideStop $stop): JsonResponse
    {
        $this->authorize('deleteStop', [$ride, $stop]);

        $this->action->handle($ride, $stop);

        return ApiResponse::success(
            message: __('messages.ride.stop_deleted'),
        );
    }
}
