<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\ReferralData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Rider\GetReferralsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Referrals', 'Get paginated list of users referred by the current rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Referrals retrieved successfully.')]
final class GetReferralsController extends Controller
{
    public function __construct(
        private readonly GetReferralsQueryInterface $query,
    ) {}

    public function __invoke(
        Request $request,
        #[CurrentUser] User $user,
    ): JsonResponse {
        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, User> $referrals */
        $referrals = $this->query->execute($user, $perPage);

        $referrals->through(
            fn (User $referral) => ReferralData::fromModel($referral),
        );

        return ApiResponse::success($referrals);
    }
}
