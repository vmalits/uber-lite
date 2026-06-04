<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\BlockDriverAction;
use App\Data\Rider\BlockedDriverResponseData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\BlockDriverRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Block Driver', 'Block a driver from being matched with the rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Driver blocked successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 422, description: 'Validation errors')]
final class BlockDriverController extends Controller
{
    public function __construct(
        private readonly BlockDriverAction $blockDriverAction,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        BlockDriverRequest $request,
    ): JsonResponse {
        $data = $request->toData();
        $blocked = $this->blockDriverAction->handle($user, $data);

        return ApiResponse::success(
            data: [
                'blocked' => BlockedDriverResponseData::fromModel($blocked),
            ],
            message: __('messages.blocked.driver_blocked'),
        );
    }
}
