<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\User\UserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\GetDriversRequest;
use App\Models\User;
use App\Queries\Admin\GetDriversQueryInterface;
use App\Services\Avatar\AvatarUrlService;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('per_page', 'integer', 'Number of items per page', false, 15)]
#[QueryParam('status', 'string', 'Filter by user status (active, inactive, banned)', false)]
#[QueryParam('banned', 'boolean', 'Filter banned drivers (true/false)', false)]
#[QueryParam('filter[phone]', 'string', 'Filter by phone number (partial match)', false)]
#[QueryParam('filter[email]', 'string', 'Filter by email (partial match)', false)]
#[QueryParam('filter[first_name]', 'string', 'Filter by first name (partial match)', false)]
#[QueryParam('filter[last_name]', 'string', 'Filter by last name (partial match)', false)]
#[QueryParam('sort', 'string', 'Sort field (prefix with - for descending)', false)]
#[Response(status: 200, description: 'Paginated drivers list retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
final class GetDriversController extends Controller
{
    public function __construct(
        private readonly GetDriversQueryInterface $getDriversQuery,
        private readonly AvatarUrlService $avatarResolver,
    ) {}

    public function __invoke(GetDriversRequest $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, User> $drivers */
        $drivers = $this->getDriversQuery->execute($perPage);

        $drivers->through(
            fn (User $user) => UserData::fromModel($user, $this->avatarResolver),
        );

        /** @var LengthAwarePaginator<int, mixed> $drivers */
        return ApiResponse::success($drivers);
    }
}
