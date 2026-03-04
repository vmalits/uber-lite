<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\SetDefaultPaymentMethodAction;
use App\Data\Rider\PaymentMethodData;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Default payment method set successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Payment method not found')]
final class SetDefaultPaymentMethodController extends Controller
{
    public function __construct(
        private readonly SetDefaultPaymentMethodAction $action,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        PaymentMethod $method,
    ): JsonResponse {
        $this->authorize('update', $method);

        $paymentMethod = $this->action->handle($user, $method);

        return ApiResponse::success(
            data: PaymentMethodData::fromModel($paymentMethod),
            message: __('messages.payment_method.set_default'),
        );
    }
}
