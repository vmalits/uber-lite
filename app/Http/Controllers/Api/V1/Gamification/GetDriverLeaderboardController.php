<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Gamification;

use App\Http\Controllers\Controller;
use App\Queries\Gamification\GetDriverLeaderboardQuery;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Gamification')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Endpoint('Get Driver Leaderboard', 'Get top drivers ranked by rating or total rides')]
#[QueryParam(
    name: 'sort',
    type: 'string',
    description: 'Sort by "-rating" (descending) or "-total_rides"',
    example: '-rating',
)]
#[QueryParam(
    name: 'limit',
    type: 'int',
    description: 'Number of drivers to return (max 50)',
    example: '10',
)]
#[Response([
    'success' => true,
    'data'    => [
        [
            'id'           => '01HQXYZABC1234567890DEFGHI',
            'first_name'   => 'John',
            'last_name'    => 'Doe',
            'avatar_paths' => null,
            'rating'       => 4.95,
            'total_rides'  => 1250,
            'rank'         => 1,
        ],
    ],
], status: 200)]
final class GetDriverLeaderboardController extends Controller
{
    public function __construct(
        private readonly GetDriverLeaderboardQuery $getDriverLeaderboardQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $limit = min((int) $request->query('limit', 10), 50);

        $leaderboard = $this->getDriverLeaderboardQuery->execute($limit);

        return ApiResponse::success($leaderboard);
    }
}
