<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\AssignDriverAction;
use App\Data\Rider\RideData;
use App\Exceptions\Ride\InvalidRideTransition;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\AssignDriverRequest;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Assign Driver to Ride', 'Manually assign a driver to a pending ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class AssignDriverController extends Controller
{
    public function __construct(
        private readonly AssignDriverAction $assignDriver,
    ) {}

    public function __invoke(AssignDriverRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('assignDriver', $ride);

        /** @var array{driver_id: string} $validated */
        $validated = $request->validated();

        try {
            $ride = $this->assignDriver->handle(
                $ride,
                $validated['driver_id'],
            );
        } catch (InvalidRideTransition $e) {
            return ApiResponse::error(
                message: $e->getMessage(),
                status: 422,
            );
        }

        return ApiResponse::success(
            data: RideData::fromModel($ride->load('rating')),
            message: __('messages.ride.driver_assigned'),
        );
    }
}
