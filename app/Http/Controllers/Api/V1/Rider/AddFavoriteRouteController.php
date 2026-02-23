<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\AddFavoriteRouteAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\AddFavoriteRouteRequest;
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
#[Response(status: 201, description: 'Favorite route created successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 422, description: 'Validation failed')]
final class AddFavoriteRouteController extends Controller
{
    public function __construct(
        private readonly AddFavoriteRouteAction $addFavoriteRoute,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        AddFavoriteRouteRequest $request,
    ): JsonResponse {
        $data = $request->toData();

        $route = $this->addFavoriteRoute->handle($user, $data);

        return ApiResponse::created(
            data: [
                'id'                  => $route->id,
                'name'                => $route->name,
                'origin_address'      => $route->origin_address,
                'destination_address' => $route->destination_address,
                'type'                => $route->type,
            ],
            message: __('messages.rider.favorite_route_added'),
        );
    }
}
