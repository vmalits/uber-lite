<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Driver\GetScheduleQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Schedule', 'Get driver\'s working schedules')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'List of schedules.')]
#[Response(status: 401, description: 'Unauthorized - Invalid or missing token')]
final class GetScheduleController extends Controller
{
    public function __construct(
        private readonly GetScheduleQueryInterface $getScheduleQuery,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $schedules = $this->getScheduleQuery->execute($user);

        return ApiResponse::success($schedules);
    }
}
