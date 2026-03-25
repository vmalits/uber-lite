<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Queries\Rider\GetRideTrackingQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Track Ride', 'Get real-time driver location for active ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Ride tracking data retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Ride not found.')]
final class TrackRideController extends Controller
{
    public function __construct(
        private readonly GetRideTrackingQueryInterface $getRideTrackingQuery,
    ) {}

    public function __invoke(Ride $ride): JsonResponse
    {
        $this->authorize('track', $ride);

        return ApiResponse::success(
            $this->getRideTrackingQuery->execute($ride),
        );
    }
}
