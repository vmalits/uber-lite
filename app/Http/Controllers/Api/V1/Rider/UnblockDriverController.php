<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\UnblockDriverAction;
use App\Http\Controllers\Controller;
use App\Models\BlockedDriver;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Endpoint('Unblock Driver', 'Remove a driver from the blocked list')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'blocked',
    type: 'string',
    description: 'ULID of the blocked driver record.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v6v9v6v9v6v9',
)]
#[Response(status: 200, description: 'Driver unblocked successfully')]
#[Response(status: 404, description: 'Blocked driver record not found')]
#[Response(status: 403, description: 'Can only unblock own blocked drivers')]
final class UnblockDriverController extends Controller
{
    public function __construct(
        private readonly UnblockDriverAction $unblockDriverAction,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        BlockedDriver $blocked,
    ): JsonResponse {
        $this->authorize('delete', $blocked);

        $this->unblockDriverAction->handle($user, $blocked);

        return ApiResponse::success(
            message: __('messages.blocked.driver_unblocked'),
        );
    }
}
