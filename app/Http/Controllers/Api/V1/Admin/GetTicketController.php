<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Support\SupportTicketData;
use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Support ticket details retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
#[Response(status: 404, description: 'Support ticket not found.')]
final class GetTicketController extends Controller
{
    public function __invoke(SupportTicket $ticket): JsonResponse
    {
        $this->authorize('view', $ticket);

        return ApiResponse::success(SupportTicketData::fromModel($ticket));
    }
}
