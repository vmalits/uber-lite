<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\FavoriteRouteData;
use App\Http\Controllers\Controller;
use App\Models\FavoriteRoute;
use App\Models\User;
use App\Queries\Rider\GetFavoriteRoutesQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Favorite Routes', 'Get all rider\'s favorite routes')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Favorite routes retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetFavoriteRoutesController extends Controller
{
    public function __construct(
        private readonly GetFavoriteRoutesQueryInterface $getFavoriteRoutesQuery,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        Request $request,
    ): JsonResponse {
        $this->authorize('viewAny', FavoriteRoute::class);

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, FavoriteRoute> $routes */
        $routes = $this->getFavoriteRoutesQuery->execute($user, $perPage);

        $routes->through(
            fn (FavoriteRoute $route): FavoriteRouteData => FavoriteRouteData::fromModel($route),
        );

        /** @var LengthAwarePaginator<int, mixed> $routes */
        return ApiResponse::success($routes);
    }
}
