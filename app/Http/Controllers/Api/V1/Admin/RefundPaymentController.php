<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\RefundPaymentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\RefundPaymentRequest;
use App\Models\PaymentAttempt;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Refund Payment', 'Refund a completed payment and return credits to the user')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Payment refunded successfully.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
#[Response(status: 422, description: 'Payment cannot be refunded.')]
final class RefundPaymentController extends Controller
{
    public function __construct(
        private readonly RefundPaymentAction $refundPayment,
    ) {}

    public function __invoke(RefundPaymentRequest $request, PaymentAttempt $paymentAttempt): JsonResponse
    {
        $this->authorize('refund', $paymentAttempt);

        /** @var string $reason */
        $reason = $request->validated('reason') ?? 'Admin initiated refund';

        $paymentAttempt = $this->refundPayment->handle(
            paymentAttempt: $paymentAttempt,
            reason: $reason,
        );

        return ApiResponse::success(
            data: [
                'id'               => $paymentAttempt->id,
                'status'           => $paymentAttempt->status->value,
                'amount'           => $paymentAttempt->amount,
                'credits_refunded' => $paymentAttempt->credits_used,
                'reason'           => $paymentAttempt->failure_reason,
            ],
            message: __('messages.payment.refunded'),
        );
    }
}
