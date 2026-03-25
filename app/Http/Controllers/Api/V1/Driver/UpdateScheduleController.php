<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\UpdateScheduleAction;
use App\Data\Driver\UpdateScheduleData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\UpdateScheduleRequest;
use App\Models\DriverSchedule;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Update Schedule', 'Update an existing working schedule')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Schedule updated successfully.')]
#[Response(status: 404, description: 'Schedule not found.')]
#[Response(status: 422, description: 'Validation error.')]
final class UpdateScheduleController extends Controller
{
    public function __construct(
        private readonly UpdateScheduleAction $updateScheduleAction,
    ) {}

    public function __invoke(UpdateScheduleRequest $request, DriverSchedule $schedule): JsonResponse
    {
        $data = UpdateScheduleData::from($request->validated());

        $updatedSchedule = $this->updateScheduleAction->handle($schedule, $data);

        return ApiResponse::success(
            data: $updatedSchedule,
            message: __('messages.driver.schedule.updated'),
        );
    }
}
