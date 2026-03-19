<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\ProcessRidePaymentWithCreditsAction;
use App\Http\Controllers\Controller;
use App\Models\Ride;
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
#[Endpoint('Pay with Credits', 'Pay for a completed ride entirely using credit balance')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam('ride', type: 'string', description: 'ULID of the ride', required: true)]
#[Response(status: 200, description: 'Payment processed successfully.')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Ride not found')]
#[Response(status: 422, description: 'Validation failed or insufficient credits')]
final class PayRideWithCreditsController extends Controller
{
    public function __construct(
        private readonly ProcessRidePaymentWithCreditsAction $action,
    ) {}

    public function __invoke(Ride $ride, #[CurrentUser] User $user): JsonResponse
    {
        $this->authorize('pay', $ride);

        $result = $this->action->handle(
            user: $user,
            ride: $ride,
        );

        return ApiResponse::success(
            data: $result,
            message: __('messages.payment.completed'),
        );
    }
}
