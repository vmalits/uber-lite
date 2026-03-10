<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Driver\DriverTipData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\GetTipHistoryRequest;
use App\Models\RideTip;
use App\Models\User;
use App\Queries\Driver\GetTipHistoryQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Tip History', 'Get history of tips received by driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Tip history retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetTipHistoryController extends Controller
{
    public function __construct(
        private readonly GetTipHistoryQueryInterface $getTipHistoryQuery,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        GetTipHistoryRequest $request,
    ): JsonResponse {
        $this->authorize('viewAny', RideTip::class);

        /** @var LengthAwarePaginator<int, RideTip> $tips */
        $tips = $this->getTipHistoryQuery->execute(
            driver: $user,
            perPage: $request->perPage(),
            from: $request->from(),
            to: $request->to(),
        );

        $tips->through(
            fn (RideTip $tip): DriverTipData => DriverTipData::fromModel($tip),
        );

        /** @var LengthAwarePaginator<int, mixed> $tips */
        return ApiResponse::success($tips);
    }
}
