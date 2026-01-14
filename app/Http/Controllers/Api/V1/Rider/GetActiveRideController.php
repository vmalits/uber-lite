<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Rider\GetActiveRideQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[ResponseFromFile('docs/examples/get_active_ride.json', status: 200)]
#[Response(status: 200, description: 'No active ride found - Returns null data with message')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have rider role or profile incomplete')]
final class GetActiveRideController extends Controller
{
    public function __construct(
        private readonly GetActiveRideQueryInterface $getActiveRideQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Ride::class);

        /** @var User $user */
        $user = $request->user();

        $activeRide = $this->getActiveRideQuery->execute($user);

        if ($activeRide === null) {
            return ApiResponse::success(
                data: null,
                message: __('messages.ride.not_active'),
            );
        }

        return ApiResponse::success(
            data: RideData::fromModel($activeRide),
        );
    }
}
