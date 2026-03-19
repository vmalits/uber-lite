<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\AddPaymentMethodAction;
use App\Data\Rider\AddPaymentMethodData;
use App\Data\Rider\PaymentMethodData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\AddPaymentMethodRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Add Payment Method', 'Add a new payment method (card, Apple Pay, Google Pay)')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[BodyParam('type', 'string', 'Payment method type: card, apple_pay, google_pay', required: true, example: 'card')]
#[BodyParam('provider', 'string', 'Payment provider: stripe, paypal', required: true, example: 'stripe')]
#[BodyParam('token', 'string', 'Provider token from client SDK', required: true, example: 'pm_1AbCdEf...')]
#[BodyParam('last_four', 'string', 'Last 4 digits of the card', required: true, example: '4242')]
#[BodyParam('card_brand', 'string', 'Card brand', required: true, example: 'visa')]
#[BodyParam('expiry_month', 'integer', 'Expiry month (1-12)', required: false, example: 12)]
#[BodyParam('expiry_year', 'integer', 'Expiry year', required: false, example: 2027)]
#[BodyParam('holder_name', 'string', 'Card holder name', required: false, example: 'John Doe')]
#[Response(status: 201, description: 'Payment method added successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 422, description: 'Validation failed')]
final class AddPaymentMethodController extends Controller
{
    public function __construct(
        private readonly AddPaymentMethodAction $action,
    ) {}

    public function __invoke(AddPaymentMethodRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $paymentMethod = $this->action->handle(
            user: $user,
            data: AddPaymentMethodData::fromRequest($request),
        );

        return ApiResponse::created(
            data: PaymentMethodData::fromModel($paymentMethod),
            message: __('messages.payment_method.added'),
        );
    }
}
