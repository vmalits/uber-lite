<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Gamification\GetUserLevelQuery;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have rider role or profile incomplete')]
final class GetLevelController extends Controller
{
    public function __construct(
        private readonly GetUserLevelQuery $getUserLevelQuery,
    ) {}

    #[Response([
        'success' => true,
        'data'    => [
            'level'           => 3,
            'xp'              => 250,
            'tier'            => 'bronze',
            'xp_to_next_tier' => 250,
            'next_tier'       => 'silver',
            'tier_threshold'  => 0,
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
