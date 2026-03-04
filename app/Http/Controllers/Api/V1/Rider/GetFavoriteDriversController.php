<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\FavoriteDriverData;
use App\Http\Controllers\Controller;
use App\Models\FavoriteDriver;
use App\Models\User;
use App\Queries\Rider\GetFavoriteDriversQueryInterface;
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
#[Response(status: 200, description: 'Favorite drivers retrieved successfully')]
final class GetFavoriteDriversController extends Controller
{
    public function __construct(
        private readonly GetFavoriteDriversQueryInterface $getFavoriteDriversQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, FavoriteDriver> $favorites */
        $favorites = $this->getFavoriteDriversQuery->execute($user, $perPage);

        $favorites->through(
            fn (FavoriteDriver $favorite): FavoriteDriverData => FavoriteDriverData::fromModel($favorite),
        );

        /** @var LengthAwarePaginator<int, mixed> $favorites */
        return ApiResponse::success($favorites);
    }
}
