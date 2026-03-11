<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Admin\AdminPayoutData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\GetPayoutsRequest;
use App\Models\DriverPayout;
use App\Queries\Admin\GetPayoutsQueryInterface;
use App\Services\Avatar\AvatarUrlService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Get Payouts', 'Get paginated list of all payouts')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Paginated payouts list retrieved successfully.')]
final class GetPayoutsController extends Controller
{
    public function __construct(
        private readonly GetPayoutsQueryInterface $query,
        private readonly AvatarUrlService $avatarResolver,
    ) {}

    public function __invoke(GetPayoutsRequest $request): JsonResponse
    {
        $this->authorize('viewAnyAdmin', DriverPayout::class);

        $payouts = $this->query->execute($request->toData());

        $payouts->through(
            fn (DriverPayout $payout) => AdminPayoutData::fromModel($payout, $this->avatarResolver),
        );

        return ApiResponse::success($payouts);
    }
}
