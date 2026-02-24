<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\DeleteFavoriteRouteAction;
use App\Http\Controllers\Controller;
use App\Models\FavoriteRoute;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'route',
    type: 'string',
    description: 'ULID of the favorite route.',
    required: true,
    example: '01jmk9v6v9v6v9v6v9v6v9v6v9',
)]
#[Response(status: 200, description: 'Favorite route deleted successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Favorite route not found')]
final class DeleteFavoriteRouteController extends Controller
{
    public function __construct(
        private readonly DeleteFavoriteRouteAction $deleteFavoriteRoute,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        FavoriteRoute $route,
    ): JsonResponse {
        $this->authorize('delete', $route);

        $this->deleteFavoriteRoute->handle($route);

        return ApiResponse::success(
            message: __('messages.rider.favorite_route_deleted'),
        );
    }
}
