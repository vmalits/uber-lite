<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\AddFavoriteDriverAction;
use App\Data\Rider\FavoriteDriverData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\AddFavoriteDriverRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Add Favorite Driver', 'Add a driver to rider\'s favorite drivers list')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Favorite driver added successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 422, description: 'Validation errors')]
final class AddFavoriteDriverController extends Controller
{
    public function __construct(
        private readonly AddFavoriteDriverAction $addFavoriteDriverAction,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        AddFavoriteDriverRequest $request,
    ): JsonResponse {
        $data = $request->toData();
        $favorite = $this->addFavoriteDriverAction->handle($user, $data);

        return ApiResponse::success(
            data: [
                'favorite' => FavoriteDriverData::fromModel($favorite),
            ],
            message: __('messages.favorite.driver_added'),
        );
    }
}
