<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\UpdatePayoutStatusAction;
use App\Data\Admin\AdminPayoutData;
use App\Http\Controllers\Controller;
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
#[Endpoint('Approve Payout', 'Approve a pending payout')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(name: 'payout', type: 'string', description: 'ULID of the payout.', required: true)]
#[Response(status: 200, description: 'Payout approved successfully.')]
#[Response(status: 422, description: 'Payout is not in pending status.')]
final class ApprovePayoutController extends Controller
{
    public function __construct(
        private readonly UpdatePayoutStatusAction $action,
        private readonly AvatarUrlService $avatarResolver,
    ) {}

    public function __invoke(DriverPayout $payout): JsonResponse
    {
        $this->authorize('updateStatus', $payout);

        $payout = $this->action->approve($payout);

        return ApiResponse::success(
            AdminPayoutData::fromModel($payout, $this->avatarResolver),
            __('messages.payout.approved'),
        );
    }
}
