<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
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
#[Endpoint('Delete Payment Method', 'Remove a payment method from rider\'s account')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Payment method deleted successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Payment method not found')]
final class DeletePaymentMethodController extends Controller
{
    public function __invoke(
        #[CurrentUser] User $user,
        PaymentMethod $method,
    ): JsonResponse {
        $this->authorize('delete', $method);

        $method->delete();

        return ApiResponse::success(
            message: __('messages.payment_method.deleted'),
        );
    }
}
