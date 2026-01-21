<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Support;

use App\Data\Support\SupportTicketData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Support\GetTicketsRequest;
use App\Models\SupportTicket;
use App\Models\User;
use App\Queries\Support\GetTicketsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Support')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('per_page', 'integer', 'Number of items per page', false, 15)]
#[QueryParam('filter[status]', 'string', 'Filter by ticket status', false)]
#[QueryParam('filter[subject]', 'string', 'Filter by subject', false)]
#[QueryParam('sort', 'string', 'Sort field (prefix with - for descending)', false)]
#[Response(status: 200, description: 'Paginated support tickets list retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
final class GetTicketsController extends Controller
{
    public function __construct(
        private readonly GetTicketsQueryInterface $getTicketsQuery,
    ) {}

    public function __invoke(GetTicketsRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $this->authorize('viewAny', SupportTicket::class);

        $perPage = PaginationHelper::perPage($request);

        $tickets = $this->getTicketsQuery->execute($user, $perPage);

        $data = $tickets->through(
            static fn (SupportTicket $ticket): SupportTicketData => SupportTicketData::fromModel($ticket),
        );

        /** @var LengthAwarePaginator<int, mixed> $data */
        return ApiResponse::success($data);
    }
}
