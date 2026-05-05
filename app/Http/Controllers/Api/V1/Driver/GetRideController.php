<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Queries\Driver\GetRideQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Ride', 'Get details of a specific ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Ride details.')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have driver role')]
#[Response(status: 404, description: 'Ride not found')]
final class GetRideController extends Controller
{
    public function __construct(
        private readonly GetRideQueryInterface $getRideQuery,
    ) {}

    public function __invoke(Ride $ride): JsonResponse
    {
        $this->authorize('view', $ride);

        $ride = $this->getRideQuery->execute($ride);

        return ApiResponse::success(
            data: RideData::fromModel($ride),
        );
    }
}
