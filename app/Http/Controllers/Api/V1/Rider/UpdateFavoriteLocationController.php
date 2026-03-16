<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\UpdateFavoriteLocationAction;
use App\Data\Rider\FavoriteLocationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\UpdateFavoriteLocationRequest;
use App\Models\FavoriteLocation;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Endpoint('Update Favorite Location', 'Update an existing favorite location')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'favorite',
    type: 'string',
    description: 'ULID of the favorite location.',
    required: true,
    example: '01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z',
)]
#[Response(status: 200, description: 'Favorite location updated successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Favorite location not found.')]
#[Response(status: 422, description: 'Validation errors.')]
final class UpdateFavoriteLocationController extends Controller
{
    public function __construct(
        private readonly UpdateFavoriteLocationAction $updateFavoriteLocation,
    ) {}

    public function __invoke(
        FavoriteLocation $favorite,
        UpdateFavoriteLocationRequest $request,
    ): JsonResponse {
        $this->authorize('update', $favorite);

        $favorite = $this->updateFavoriteLocation->handle($favorite, $request->toData());

        return ApiResponse::success(
            data: FavoriteLocationData::fromModel($favorite),
        );
    }
}
