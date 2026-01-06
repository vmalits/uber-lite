<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Rider\GetRideStatsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[ResponseFromFile('docs/examples/rider_stats.json', status: 200)]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have rider role or profile incomplete')]
final class GetRideStatsController extends Controller
{
    public function __construct(
        private readonly GetRideStatsQueryInterface $getRideStatsQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Ride::class);

        /** @var User $user */
        $user = $request->user();

        $stats = $this->getRideStatsQuery->execute($user);

        return ApiResponse::success($stats);
    }
}
