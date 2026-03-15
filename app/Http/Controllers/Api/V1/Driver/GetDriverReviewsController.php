<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Rider\DriverReviewData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\GetDriverReviewsRequest;
use App\Models\RideRating;
use App\Models\User;
use App\Queries\Driver\GetDriverReviewsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Driver Reviews', 'Get reviews received by the authenticated driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Driver reviews retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetDriverReviewsController extends Controller
{
    public function __construct(
        private readonly GetDriverReviewsQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        GetDriverReviewsRequest $request,
    ): JsonResponse {
        $this->authorize('viewAny', RideRating::class);

        /** @var LengthAwarePaginator<int, RideRating> $reviews */
        $reviews = $this->query->execute(
            driverId: $user->id,
            perPage: $request->perPage(),
        );

        $reviews->through(
            fn (RideRating $rating): DriverReviewData => DriverReviewData::fromModel($rating),
        );

        /** @var LengthAwarePaginator<int, mixed> $reviews */
        return ApiResponse::success($reviews);
    }
}
