<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Queries\Rider\GetPaymentStatusQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Endpoint('Get Payment Status', 'Get the current payment status for a ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam('ride', type: 'string', description: 'ULID of the ride', required: true)]
#[Response(status: 200, description: 'Payment status retrieved successfully.')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Ride not found')]
final class GetPaymentStatusController extends Controller
{
    public function __construct(
        private readonly GetPaymentStatusQueryInterface $query,
    ) {}

    public function __invoke(Ride $ride): JsonResponse
    {
        $this->authorize('view', $ride);

        $payment = $this->query->execute($ride);

        return ApiResponse::success(
            data: $payment,
            message: __('messages.payment.status_retrieved'),
        );
    }
}
