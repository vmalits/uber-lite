<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\DeleteScheduleAction;
use App\Http\Controllers\Controller;
use App\Models\DriverSchedule;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Delete Schedule', 'Delete a working schedule')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Schedule deleted successfully.')]
#[Response(status: 404, description: 'Schedule not found.')]
final class DeleteScheduleController extends Controller
{
    public function __construct(
        private readonly DeleteScheduleAction $deleteScheduleAction,
    ) {}

    public function __invoke(DriverSchedule $schedule): JsonResponse
    {
        $this->authorize('delete', $schedule);

        $this->deleteScheduleAction->handle($schedule);

        return ApiResponse::success(
            message: __('messages.driver.schedule.deleted'),
        );
    }
}
