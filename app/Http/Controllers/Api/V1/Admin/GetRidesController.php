<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\GetRidesRequest;
use App\Models\Ride;
use App\Queries\Admin\GetRidesQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('per_page', 'integer', 'Number of items per page', false, 15)]
#[QueryParam('status', 'string', 'Filter by ride status', false)]
#[QueryParam('filter[rider_id]', 'string', 'Filter by rider ID', false)]
#[QueryParam('filter[driver_id]', 'string', 'Filter by driver ID', false)]
#[QueryParam('sort', 'string', 'Sort field (prefix with - for descending)', false)]
#[Response(status: 200, description: 'Paginated rides list retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
final class GetRidesController extends Controller
{
    public function __construct(
        private readonly GetRidesQueryInterface $getRidesQuery,
    ) {}

    public function __invoke(GetRidesRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Ride::class);

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, Ride> $rides */
        $rides = $this->getRidesQuery->execute($perPage);

        $rides->through(
            fn (Ride $ride) => RideData::fromModel($ride),
        );

        /** @var LengthAwarePaginator<int, mixed> $rides */
        return ApiResponse::success($rides);
    }
}
