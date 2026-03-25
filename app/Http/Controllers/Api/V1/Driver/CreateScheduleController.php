<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\CreateScheduleAction;
use App\Data\Driver\CreateScheduleData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\CreateScheduleRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Create Schedule', 'Create a new working schedule for the driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Schedule created successfully.')]
#[Response(status: 422, description: 'Validation error.')]
final class CreateScheduleController extends Controller
{
    public function __construct(
        private readonly CreateScheduleAction $createScheduleAction,
    ) {}

    public function __invoke(CreateScheduleRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $data = CreateScheduleData::from($request->validated());

        $schedule = $this->createScheduleAction->handle($user, $data);

        return ApiResponse::success(
            data: $schedule,
            message: __('messages.driver.schedule.created'),
        );
    }
}
