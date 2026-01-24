<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\GoOnlineAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\GoOnlineRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Driver is now online.')]
#[Response(status: 422, description: 'Validation error.')]
final class GoOnlineController extends Controller
{
    public function __construct(
        private readonly GoOnlineAction $goOnlineAction,
    ) {}

    public function __invoke(GoOnlineRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $location = $this->goOnlineAction->handle(
            $user,
            $request->float('latitude'),
            $request->float('longitude'),
        );

        return ApiResponse::success(
            data: $location,
            message: __('messages.driver.online'),
        );
    }
}
