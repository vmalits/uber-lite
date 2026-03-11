<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\UpdatePayoutStatusAction;
use App\Data\Admin\AdminPayoutData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\FailPayoutRequest;
use App\Models\DriverPayout;
use App\Services\Avatar\AvatarUrlService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Admin')]
#[Endpoint('Fail Payout', 'Mark a payout as failed')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(name: 'payout', type: 'string', description: 'ULID of the payout.', required: true)]
#[Response(status: 200, description: 'Payout marked as failed.')]
#[Response(status: 422, description: 'Payout cannot be failed from current status.')]
final class FailPayoutController extends Controller
{
    public function __construct(
        private readonly UpdatePayoutStatusAction $action,
        private readonly AvatarUrlService $avatarResolver,
    ) {}

    public function __invoke(DriverPayout $payout, FailPayoutRequest $request): JsonResponse
    {
        $this->authorize('updateStatus', $payout);

        $payout = $this->action->fail($payout, $request->reason());

        return ApiResponse::success(
            AdminPayoutData::fromModel($payout, $this->avatarResolver),
            __('messages.payout.failed'),
        );
    }
}
