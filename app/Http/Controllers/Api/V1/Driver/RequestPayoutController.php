<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\RequestPayoutAction;
use App\Data\Driver\DriverPayoutData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\RequestPayoutRequest;
use App\Models\DriverPayout;
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
#[Endpoint('Request Payout', 'Request a payout of driver balance')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Payout requested successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 422, description: 'Validation failed or insufficient balance')]
final class RequestPayoutController extends Controller
{
    public function __construct(
        private readonly RequestPayoutAction $action,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        RequestPayoutRequest $request,
    ): JsonResponse {
        $this->authorize('create', DriverPayout::class);

        $payout = $this->action->handle(
            driver: $user,
            data: $request->toData(),
        );

        return ApiResponse::success(
            data: DriverPayoutData::fromModel($payout),
            message: __('messages.payout.requested'),
        );
    }
}
