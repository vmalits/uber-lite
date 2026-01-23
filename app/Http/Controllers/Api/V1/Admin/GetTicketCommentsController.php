<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Support\SupportTicketCommentData;
use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Queries\Support\GetTicketCommentsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Support ticket comments retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
#[Response(status: 404, description: 'Support ticket not found.')]
final class GetTicketCommentsController extends Controller
{
    public function __construct(
        private readonly GetTicketCommentsQueryInterface $getTicketCommentsQuery,
    ) {}

    public function __invoke(SupportTicket $ticket): JsonResponse
    {
        $this->authorize('view', $ticket);

        $comments = $this->getTicketCommentsQuery->execute($ticket);

        /** @var array<string, mixed> $data */
        $data = SupportTicketCommentData::collect($comments)->toArray();

        return ApiResponse::success($data);
    }
}
