<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Gamification;

use App\Http\Controllers\Controller;
use App\Queries\Gamification\GetRiderLeaderboardQuery;
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
#[Endpoint('Get Rider Leaderboard', 'Get top riders ranked by streak or total rides')]
#[QueryParam(
    name: 'sort',
    type: 'string',
    description: 'Sort by "-current_streak" (descending) or "-total_rides"',
    example: '-current_streak',
)]
#[QueryParam(
    name: 'limit',
    type: 'int',
    description: 'Number of riders to return (max 50)',
    example: '10',
)]
#[Response([
    'success' => true,
    'data'    => [
        [
            'id'             => '01HQXYZABC1234567890DEFGHI',
            'first_name'     => 'John',
            'last_name'      => 'Doe',
            'avatar_paths'   => null,
            'current_streak' => 15,
            'total_rides'    => 250,
            'rank'           => 1,
        ],
    ],
], status: 200)]
final class GetRiderLeaderboardController extends Controller
{
    public function __construct(
        private readonly GetRiderLeaderboardQuery $getRiderLeaderboardQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $limit = min((int) $request->query('limit', 10), 50);

        $leaderboard = $this->getRiderLeaderboardQuery->execute($limit);

        return ApiResponse::success($leaderboard);
    }
}
