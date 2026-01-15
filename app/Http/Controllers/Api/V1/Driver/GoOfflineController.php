<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\GoOfflineAction;
use App\Data\Driver\DriverLocationData;
use App\Http\Controllers\Controller;
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
#[Response(status: 200, description: 'Driver is now offline.')]
final class GoOfflineController extends Controller
{
    public function __construct(
        private readonly GoOfflineAction $goOfflineAction,
    ) {}

    public function __invoke(): JsonResponse
    {
        /** @var User $user */
        $user = request()->user();

        $location = $this->goOfflineAction->handle($user);

        return ApiResponse::success(
            data: DriverLocationData::fromModel($location),
            message: __('messages.driver.offline'),
        );
    }
}
