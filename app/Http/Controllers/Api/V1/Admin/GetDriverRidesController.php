<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\GetRidesRequest;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Admin\GetDriverRidesQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Get Driver Rides', 'Get paginated list of rides for a specific driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Paginated driver rides list.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
#[Response(status: 404, description: 'Driver not found.')]
final class GetDriverRidesController extends Controller
{
    public function __construct(
        private readonly GetDriverRidesQueryInterface $getDriverRidesQuery,
    ) {}

    public function __invoke(GetRidesRequest $request, User $driver): JsonResponse
    {
        $this->authorize('viewAny', Ride::class);

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, Ride> $rides */
        $rides = $this->getDriverRidesQuery->execute($driver->id, $perPage);

        $rides->through(
            fn (Ride $ride) => RideData::fromModel($ride),
        );

        /** @var LengthAwarePaginator<int, mixed> $rides */
        return ApiResponse::success($rides);
    }
}
