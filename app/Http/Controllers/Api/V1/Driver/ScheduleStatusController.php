<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\GetScheduleStatusAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Schedule Status', 'Get current schedule status including active and next slots')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Schedule status retrieved.')]
#[Response(status: 401, description: 'Unauthorized')]
final class ScheduleStatusController extends Controller
{
    public function __construct(
        private readonly GetScheduleStatusAction $getScheduleStatusAction,
    ) {}

    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        $status = $this->getScheduleStatusAction->handle($user);

        return ApiResponse::success(
            data: $status,
            message: __('messages.driver.schedule.status_retrieved'),
        );
    }
}
