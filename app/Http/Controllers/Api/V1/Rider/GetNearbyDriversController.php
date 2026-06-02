<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\NearbyDriversResponseData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\GetNearbyDriversRequest;
use App\Queries\Rider\GetNearbyDriversQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Endpoint('Get Nearby Drivers', 'Get available drivers near a given location with estimated pickup times')]
#[QueryParam('lat', type: 'number', description: 'Search center latitude', required: true, example: 47.0105)]
#[QueryParam('lng', type: 'number', description: 'Search center longitude', required: true, example: 28.8638)]
#[QueryParam('radius', type: 'integer', description: 'Search radius in meters (default: 3000, max: 10000)', example: 5000)]
#[QueryParam('vehicle_type', type: 'string', description: 'Filter by vehicle type', example: 'sedan')]
#[Response(status: 200, description: 'Nearby drivers retrieved successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class GetNearbyDriversController extends Controller
{
    public function __construct(
        private readonly GetNearbyDriversQueryInterface $nearbyDriversQuery,
    ) {}

    public function __invoke(GetNearbyDriversRequest $request): JsonResponse
    {
        $data = $request->toData();

        $drivers = $this->nearbyDriversQuery->execute($data);

        return ApiResponse::success(
            NearbyDriversResponseData::fromDrivers(
                drivers: $drivers,
                searchLat: $data->lat,
                searchLng: $data->lng,
                radiusMeters: $data->radiusMeters,
            ),
        );
    }
}
