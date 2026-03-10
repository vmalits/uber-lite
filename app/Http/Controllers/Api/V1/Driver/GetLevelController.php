<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Gamification\GetUserLevelQuery;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Level', 'Get driver\'s current level information')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have driver role or profile incomplete')]
final class GetLevelController extends Controller
{
    public function __construct(
        private readonly GetUserLevelQuery $getUserLevelQuery,
    ) {}

    #[Response([
        'success' => true,
        'data'    => [
            'level'           => 5,
            'xp'              => 520,
            'tier'            => 'silver',
            'xp_to_next_tier' => 1480,
            'next_tier'       => 'gold',
            'tier_threshold'  => 500,
        ],
    ], status: 200)]
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $level = $this->getUserLevelQuery->execute($user);

        return ApiResponse::success($level);
    }
}
