<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\ConfirmWalletTopUpAction;
use App\Http\Controllers\Controller;
use App\Models\WalletTopUp;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Endpoint('Confirm Wallet Top-Up', 'Manually confirm a pending top-up (fallback for webhook)')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'topUp',
    type: 'string',
    description: 'ULID of the wallet top-up.',
    required: true,
    example: '01H5NVQ8K6MBK2W2Z6SWX4PYR0',
)]
#[Response(status: 200, description: 'Top-up confirmed successfully.')]
#[Response(status: 403, description: 'Not authorized.')]
final class ConfirmWalletTopUpController extends Controller
{
    public function __construct(
        private readonly ConfirmWalletTopUpAction $action,
    ) {}

    public function __invoke(WalletTopUp $topUp): JsonResponse
    {
        $this->authorize('confirm', $topUp);

        return ApiResponse::success($this->action->handle($topUp));
    }
}
