<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\BlockedDriverResponseData;
use App\Http\Controllers\Controller;
use App\Models\BlockedDriver;
use App\Models\User;
use App\Queries\Rider\GetBlockedDriversQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Blocked Drivers', 'Get list of rider\'s blocked drivers')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Blocked drivers retrieved successfully')]
final class GetBlockedDriversController extends Controller
{
    public function __construct(
        private readonly GetBlockedDriversQueryInterface $getBlockedDriversQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, BlockedDriver> $blockedDrivers */
        $blockedDrivers = $this->getBlockedDriversQuery->execute($user, $perPage);

        $blockedDrivers->through(
            fn (BlockedDriver $blocked): BlockedDriverResponseData => BlockedDriverResponseData::fromModel($blocked),
        );

        /** @var LengthAwarePaginator<int, mixed> $blockedDrivers */
        return ApiResponse::success($blockedDrivers);
    }
}
