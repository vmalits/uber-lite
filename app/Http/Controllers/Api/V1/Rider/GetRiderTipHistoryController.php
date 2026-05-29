<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\RiderTipData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\GetRiderTipHistoryRequest;
use App\Models\RideTip;
use App\Models\User;
use App\Queries\Rider\GetRiderTipHistoryQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Tip History', 'Get history of tips given by rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Tip history retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetRiderTipHistoryController extends Controller
{
    public function __construct(
        private readonly GetRiderTipHistoryQueryInterface $getRiderTipHistoryQuery,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        GetRiderTipHistoryRequest $request,
    ): JsonResponse {
        $this->authorize('viewAnyAsRider', RideTip::class);

        /** @var LengthAwarePaginator<int, RideTip> $tips */
        $tips = $this->getRiderTipHistoryQuery->execute(
            rider: $user,
            perPage: $request->perPage(),
            from: $request->from(),
            to: $request->to(),
        );

        $tips->through(
            fn (RideTip $tip): RiderTipData => RiderTipData::fromModel($tip),
        );

        /** @var LengthAwarePaginator<int, mixed> $tips */
        return ApiResponse::success($tips);
    }
}
