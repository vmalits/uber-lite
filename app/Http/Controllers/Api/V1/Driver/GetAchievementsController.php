<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Gamification\GetUserAchievementsQuery;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
#[Response(status: 403, description: 'Forbidden - User does not have driver role or profile incomplete')]
final class GetAchievementsController extends Controller
{
    public function __construct(
        private readonly GetUserAchievementsQuery $getUserAchievementsQuery,
    ) {}

    #[QueryParam('status', 'string', 'Filter by status: completed, in_progress', required: false, example: 'completed')]
    #[QueryParam('per_page', 'int', 'Items per page', required: false, example: 15)]
    #[Response([
        'success' => true,
        'data'    => [
            'current_page' => 1,
            'data'         => [
                [
                    'id'          => '01HQXYZ',
                    'achievement' => [
                        'id'            => '01HQABC',
                        'name'          => 'First Ride',
                        'key'           => 'driver_first_ride',
                        'description'   => 'Complete your first ride',
                        'icon'          => 'trophy',
                        'category'      => 'driver',
                        'target_value'  => 1,
                        'points_reward' => 10,
                    ],
                    'progress'            => 1,
                    'progress_percentage' => 100.0,
                    'completed_at'        => '2026-02-26T12:00:00Z',
                    'is_completed'        => true,
                ],
            ],
            'per_page' => 15,
            'total'    => 1,
        ],
    ], status: 200)]
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $status = $request->query('status');
        $perPage = (int) $request->query('per_page', 15);

        $userAchievements = $this->getUserAchievementsQuery->execute($user, $status, $perPage);

        return ApiResponse::success($userAchievements);
    }
}
