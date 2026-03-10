<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\DeleteFavoriteDriverAction;
use App\Http\Controllers\Controller;
use App\Models\FavoriteDriver;
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
#[Endpoint('Delete Favorite Driver', 'Remove a driver from favorites list')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'favorite',
    type: 'string',
    description: 'ULID of favorite driver.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v6v9v6v9v6v9',
)]
#[Response(status: 200, description: 'Favorite driver deleted successfully')]
#[Response(status: 404, description: 'Favorite driver not found')]
#[Response(status: 403, description: 'Can only delete own favorite drivers')]
final class DeleteFavoriteDriverController extends Controller
{
    public function __construct(
        private readonly DeleteFavoriteDriverAction $deleteFavoriteDriverAction,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        FavoriteDriver $favorite,
    ): JsonResponse {
        $this->authorize('delete', $favorite);

        $this->deleteFavoriteDriverAction->handle($user, $favorite);

        return ApiResponse::success(
            message: __('messages.favorite.driver_removed'),
        );
    }
}
