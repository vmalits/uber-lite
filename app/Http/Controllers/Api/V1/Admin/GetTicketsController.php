<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Support\SupportTicketData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\GetTicketsRequest;
use App\Models\SupportTicket;
use App\Queries\Admin\GetTicketsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Paginated support tickets list retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
final class GetTicketsController extends Controller
{
    public function __construct(
        private readonly GetTicketsQueryInterface $getTicketsQuery,
    ) {}

    public function __invoke(GetTicketsRequest $request): JsonResponse
    {
        $this->authorize('viewAny', SupportTicket::class);

        $perPage = PaginationHelper::perPage($request);

        $tickets = $this->getTicketsQuery->execute($perPage);

        $data = $tickets->through(
            static fn (SupportTicket $ticket): SupportTicketData => SupportTicketData::fromModel($ticket),
        );

        /** @var LengthAwarePaginator<int, mixed> $data */
        return ApiResponse::success($data);
    }
}
