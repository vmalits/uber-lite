<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Queries\Rider\GetRideTimelineQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Ride Timeline', 'Get the status timeline for a ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Ride timeline retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Ride not found.')]
final class GetRideTimelineController extends Controller
{
    public function __construct(
        private readonly GetRideTimelineQueryInterface $getRideTimelineQuery,
    ) {}

    public function __invoke(Ride $ride): JsonResponse
    {
        $this->authorize('view', $ride);

        return ApiResponse::success(
            data: $this->getRideTimelineQuery->execute($ride),
        );
    }
}
