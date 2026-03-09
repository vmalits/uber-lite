<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\DriverReviewData;
use App\Http\Controllers\Controller;
use App\Models\RideRating;
use App\Models\User;
use App\Queries\Rider\GetDriverReviewsQueryInterface;
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
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'driver',
    type: 'string',
    description: 'ULID of the driver.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v',
)]
#[QueryParam('sort', 'string', 'Sort by field. Allowed: created_at, rating. Prefix with - for descending. Default: -created_at', required: false, example: '-created_at')]
#[QueryParam('per_page', 'int', 'Number of items per page. Default: 15', required: false, example: '15')]
#[ResponseFromFile('docs/examples/driver_reviews.json', status: 200)]
#[Response(status: 404, description: 'Driver not found.')]
final class GetDriverReviewsController extends Controller
{
    public function __construct(
        private readonly GetDriverReviewsQueryInterface $query,
    ) {}

    public function __invoke(Request $request, User $driver): JsonResponse
    {
        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, RideRating> $reviews */
        $reviews = $this->query->execute($driver, $perPage);

        $reviews->through(
            fn (RideRating $rating): DriverReviewData => DriverReviewData::fromModel($rating),
        );

        /** @var LengthAwarePaginator<int, mixed> $reviews */
        return ApiResponse::success($reviews);
    }
}
