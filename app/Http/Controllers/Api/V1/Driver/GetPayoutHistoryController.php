<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Driver\DriverPayoutData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\GetPayoutHistoryRequest;
use App\Models\DriverPayout;
use App\Models\User;
use App\Queries\Driver\GetPayoutHistoryQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Payout history retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetPayoutHistoryController extends Controller
{
    public function __construct(
        private readonly GetPayoutHistoryQueryInterface $getPayoutHistoryQuery,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        GetPayoutHistoryRequest $request,
    ): JsonResponse {
        $this->authorize('viewAny', DriverPayout::class);

        /** @var LengthAwarePaginator<int, DriverPayout> $payouts */
        $payouts = $this->getPayoutHistoryQuery->execute(
            driver: $user,
            perPage: $request->perPage(),
            from: $request->from(),
            to: $request->to(),
            status: $request->status(),
        );

        $payouts->through(
            fn (DriverPayout $payout): DriverPayoutData => DriverPayoutData::fromModel($payout),
        );

        /** @var LengthAwarePaginator<int, mixed> $payouts */
        return ApiResponse::success($payouts);
    }
}
