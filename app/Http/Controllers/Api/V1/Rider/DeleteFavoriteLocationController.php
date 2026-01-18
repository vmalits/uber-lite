<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\DeleteFavoriteLocationAction;
use App\Http\Controllers\Controller;
use App\Models\FavoriteLocation;
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
    name: 'favorite',
    type: 'string',
    description: 'ULID of favorite location.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v6v9v6v9v6v9',
)]
#[Response(status: 200, description: 'Favorite location deleted successfully')]
#[Response(status: 404, description: 'Favorite location not found')]
#[Response(status: 403, description: 'Can only delete own favorite locations')]
final class DeleteFavoriteLocationController extends Controller
{
    public function __construct(
        private readonly DeleteFavoriteLocationAction $deleteFavoriteLocationAction,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        FavoriteLocation $favorite,
    ): JsonResponse {
        $this->authorize('delete', $favorite);

        $this->deleteFavoriteLocationAction->handle($user, $favorite);

        return ApiResponse::success(
            message: __('messages.favorite.location_removed'),
        );
    }
}
