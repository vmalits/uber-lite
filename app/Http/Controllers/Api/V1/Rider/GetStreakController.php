<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Streak\GetRideStreakQuery;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Streak', 'Get rider\'s current ride streak information')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response([
    'success' => true,
    'data'    => [
        'current_streak'     => 7,
        'longest_streak'     => 14,
        'level'              => 'silver',
        'discount_percent'   => 10,
        'days_to_next_level' => 7,
        'last_ride_date'     => '2026-02-28',
        'is_active'          => true,
    ],
], status: 200)]
#[Response(status: 401, description: 'Unauthorized')]
#[Response(status: 403, description: 'Forbidden')]
final class GetStreakController extends Controller
{
    public function __construct(
        private readonly GetRideStreakQuery $getRideStreakQuery,
    ) {}

    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        $streak = $this->getRideStreakQuery->execute($user);

        return ApiResponse::success($streak);
    }
}
