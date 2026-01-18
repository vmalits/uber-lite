<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Queries\Admin\GetRideQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Ride details retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
#[Response(status: 404, description: 'Ride not found.')]
final class GetRideController extends Controller
{
    public function __construct(
        private readonly GetRideQueryInterface $query,
    ) {}

    public function __invoke(string $ride): JsonResponse
    {
        $this->authorize('viewAny', Ride::class);

        $rideData = RideData::fromModel($this->query->execute($ride));

        return ApiResponse::success($rideData);
    }
}
