<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\SearchLocationsRequest;
use App\Queries\Rider\SearchLocationsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('query', description: 'Search query (address, place name)', required: true, example: 'Stefan cel Mare')]
#[QueryParam('lat', description: 'User current latitude (for better results)', required: false, example: 47.0105)]
#[QueryParam('lng', description: 'User current longitude (for better results)', required: false, example: 28.8638)]
#[QueryParam('limit', description: 'Maximum number of results', required: false, example: 5)]
#[Response(status: 200, description: 'Locations found successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class SearchLocationsController extends Controller
{
    public function __construct(
        private readonly SearchLocationsQueryInterface $searchLocationsQuery,
    ) {}

    public function __invoke(SearchLocationsRequest $request): JsonResponse
    {
        $data = $request->toData();
        $results = $this->searchLocationsQuery->execute($data);

        return ApiResponse::success(
            data: [
                'locations' => $results,
            ],
        );
    }
}
