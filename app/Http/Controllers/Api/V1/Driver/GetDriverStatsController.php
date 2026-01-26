<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Driver\GetDriverStatsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[ResponseFromFile('docs/examples/driver_stats.json', status: 200)]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have driver role or profile incomplete')]
final class GetDriverStatsController extends Controller
{
    public function __construct(
        private readonly GetDriverStatsQueryInterface $getDriverStatsQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $stats = $this->getDriverStatsQuery->execute($user);

        return ApiResponse::success($stats);
    }
}
