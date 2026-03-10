<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\FavoriteRouteData;
use App\Http\Controllers\Controller;
use App\Models\FavoriteRoute;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Endpoint('Get Favorite Route', 'Get details of a specific favorite route')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'route',
    type: 'string',
    description: 'ULID of the favorite route.',
    required: true,
    example: '01jmk9v6v9v6v9v6v9v6v9v6v9',
)]
#[Response(status: 200, description: 'Favorite route retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Favorite route not found')]
final class GetFavoriteRouteController extends Controller
{
    public function __invoke(
        #[CurrentUser] User $user,
        FavoriteRoute $route,
    ): JsonResponse {
        $this->authorize('view', $route);

        return ApiResponse::success(
            data: FavoriteRouteData::fromModel($route),
        );
    }
}
