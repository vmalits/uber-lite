<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\StreakHistoryRequest;
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

#[Group('Driver')]
#[Endpoint('Get Streak History', 'Get history of driver\'s streaks')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('days', 'int', 'Number of days to include (7-90)', example: 30)]
#[Response(status: 200, description: 'Streak history retrieved successfully')]
#[Response(status: 401, description: 'Unauthorized')]
#[Response(status: 403, description: 'Forbidden')]
final class GetStreakHistoryController extends Controller
{
    public function __construct(
        private readonly GetStreakHistoryQueryInterface $query,
    ) {}

    public function __invoke(StreakHistoryRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $data = $this->query->execute($user, $request->days(), 'driver_id');

        return ApiResponse::success($data);
    }
}
