<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Rider\GetRideHistoryQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Paginated ride history retrieved successfully.')]
final class GetRideHistoryController extends Controller
{
    public function __construct(
        private readonly GetRideHistoryQueryInterface $getRideHistoryQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Ride::class);

        /** @var User $user */
        $user = $request->user();

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, Ride> $rides */
        $rides = $this->getRideHistoryQuery->execute($user, $perPage);

        $rides->through(
            fn (Ride $ride): RideData => RideData::fromModel($ride),
        );

        /** @var LengthAwarePaginator<int, mixed> $rides */
        return ApiResponse::success($rides);
    }
}
