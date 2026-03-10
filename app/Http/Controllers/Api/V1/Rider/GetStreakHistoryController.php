<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\StreakHistoryRequest;
use App\Models\User;
use App\Queries\Streak\GetStreakHistoryQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Streak History', 'Get history of rider\'s streaks')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('days', 'int', 'Number of days to include (7-90)', example: 30)]
#[Response([
    'success' => true,
    'data'    => [
        'current_streak' => 7,
        'longest_streak' => 14,
        'history'        => [
            ['streak_count' => 0, 'date' => '2026-02-06', 'ride_completed' => false],
            ['streak_count' => 1, 'date' => '2026-02-07', 'ride_completed' => true],
            ['streak_count' => 2, 'date' => '2026-02-08', 'ride_completed' => true],
        ],
    ],
], status: 200)]
#[Response(status: 401, description: 'Unauthorized')]
#[Response(status: 403, description: 'Forbidden')]
final class GetStreakHistoryController extends Controller
{
    public function __construct(
        private readonly GetStreakHistoryQueryInterface $query,
    ) {}

    public function __invoke(StreakHistoryRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $data = $this->query->execute($user, $request->days());

        return ApiResponse::success($data);
    }
}
