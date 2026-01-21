<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Support;

use App\Data\Support\SupportTicketData;
use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Support')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Support ticket details retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Support ticket not found.')]
final class ShowTicketController extends Controller
{
    public function __invoke(SupportTicket $ticket): JsonResponse
    {
        $this->authorize('view', $ticket);

        return ApiResponse::success(SupportTicketData::fromModel($ticket));
    }
}
