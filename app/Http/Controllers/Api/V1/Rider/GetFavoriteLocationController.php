<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\FavoriteLocationData;
use App\Http\Controllers\Controller;
use App\Models\FavoriteLocation;
use App\Models\User;
use App\Queries\Rider\GetFavoriteLocationQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Favorite location retrieved successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Favorite location not found')]
final class GetFavoriteLocationController extends Controller
{
    public function __construct(
        private readonly GetFavoriteLocationQueryInterface $getFavoriteLocationQuery,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        FavoriteLocation $favorite,
    ): JsonResponse {
        $this->authorize('view', $favorite);

        $location = $this->getFavoriteLocationQuery->execute($user->id, $favorite->id);

        if ($location === null) {
            return ApiResponse::error(
                message: __('messages.errors.favorite_location_not_found'),
                status: 404,
            );
        }

        return ApiResponse::success(
            data: FavoriteLocationData::fromModel($location),
        );
    }
}
