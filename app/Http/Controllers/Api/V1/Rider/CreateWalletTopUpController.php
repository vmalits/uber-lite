<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\CreateWalletTopUpAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\CreateWalletTopUpRequest;
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
#[Endpoint('Create Wallet Top-Up', 'Create a new wallet top-up request')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Top-up created successfully.')]
#[Response(status: 422, description: 'Validation failed.')]
final class CreateWalletTopUpController extends Controller
{
    public function __construct(
        private readonly CreateWalletTopUpAction $action,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        CreateWalletTopUpRequest $request,
    ): JsonResponse {
        $topUpData = $this->action->handle(
            user: $user,
            amount: $request->getAmount(),
            paymentMethodId: $request->getPaymentMethodId(),
        );

        return ApiResponse::created($topUpData);
    }
}
