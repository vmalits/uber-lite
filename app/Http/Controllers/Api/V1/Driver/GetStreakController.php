<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

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

#[Group('Driver')]
#[Endpoint('Get Streak', 'Get driver\'s current ride streak information')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Streak information retrieved successfully')]
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
