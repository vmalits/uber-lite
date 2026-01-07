<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
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
#[ResponseFromFile('docs/examples/get_ride.json', status: 200)]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have rider role, profile incomplete')]
#[Response(status: 404, description: 'Ride not found')]
final class GetRideController extends Controller
{
    public function __invoke(Request $request, Ride $ride): JsonResponse
    {
        $this->authorize('view', $ride);

        $ride->load('rating');

        return ApiResponse::success(
            data: RideData::fromModel($ride),
        );
    }
}
