<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\FavoriteLocationData;
use App\Http\Controllers\Controller;
use App\Models\FavoriteLocation;
use App\Models\User;
use App\Queries\Rider\GetFavoriteLocationsQueryInterface;
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
#[Response(status: 200, description: 'Favorite locations retrieved successfully')]
final class GetFavoriteLocationsController extends Controller
{
    public function __construct(
        private readonly GetFavoriteLocationsQueryInterface $getFavoriteLocationsQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, FavoriteLocation> $favorites */
        $favorites = $this->getFavoriteLocationsQuery->execute($user, $perPage);

        $favorites->through(
            fn (FavoriteLocation $favorite): FavoriteLocationData => FavoriteLocationData::fromModel($favorite),
        );

        /** @var LengthAwarePaginator<int, mixed> $favorites */
        return ApiResponse::success($favorites);
    }
}
