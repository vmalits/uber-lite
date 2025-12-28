<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Queries\Driver\GetAvailableRidesQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam(name: 'per_page', type: 'int', description: 'Page size. Default: 15.', example: 15)]
#[QueryParam(name: 'page', type: 'int', description: 'Page number.', example: 1)]
#[Response(status: 200, description: 'List of available rides retrieved successfully.')]
final class GetAvailableRidesController extends Controller
{
    public function __construct(
        private readonly GetAvailableRidesQueryInterface $getAvailableRidesQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('view-available', Ride::class);

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, Ride> $rides */
        $rides = $this->getAvailableRidesQuery->execute($perPage);

        $rides->through(
            fn (Ride $ride): RideData => RideData::fromModel($ride),
        );

        /** @var LengthAwarePaginator<int, mixed> $rides */
        return ApiResponse::success($rides);
    }
}
