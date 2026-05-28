<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Admin\AdminPaymentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\GetPaymentsRequest;
use App\Models\PaymentAttempt;
use App\Queries\Admin\GetPaymentsQueryInterface;
use App\Services\Avatar\AvatarUrlService;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Get Payments', 'Get paginated list of all payment attempts')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Paginated payments list retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
final class GetPaymentsController extends Controller
{
    public function __construct(
        private readonly GetPaymentsQueryInterface $getPaymentsQuery,
        private readonly AvatarUrlService $avatarResolver,
    ) {}

    public function __invoke(GetPaymentsRequest $request): JsonResponse
    {
        $this->authorize('viewAny', PaymentAttempt::class);

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, PaymentAttempt> $payments */
        $payments = $this->getPaymentsQuery->execute($perPage);

        $payments->through(
            fn (PaymentAttempt $payment) => AdminPaymentData::fromModel($payment, $this->avatarResolver),
        );

        /** @var LengthAwarePaginator<int, mixed> $payments */
        return ApiResponse::success($payments);
    }
}
