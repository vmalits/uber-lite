<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\AddFavoriteLocationAction;
use App\Data\Rider\FavoriteLocationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\CreateFavoriteLocationRequest;
use App\Models\User;
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
#[Response(status: 200, description: 'Favorite location added successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 422, description: 'Validation errors')]
final class AddFavoriteLocationController extends Controller
{
    public function __construct(
        private readonly AddFavoriteLocationAction $addFavoriteLocationAction,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        CreateFavoriteLocationRequest $request,
    ): JsonResponse {
        $data = $request->toData();
        $favorite = $this->addFavoriteLocationAction->handle($user, $data);

        return ApiResponse::success(
            data: [
                'favorite' => FavoriteLocationData::fromModel($favorite),
            ],
            message: __('messages.favorite.location_added'),
        );
    }
}
