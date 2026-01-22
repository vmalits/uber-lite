<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Support;

use App\Actions\Support\UpdateTicketStatusAction;
use App\Data\Support\SupportTicketData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Support\UpdateTicketStatusRequest;
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
#[Response(status: 200, description: 'Support ticket status updated successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Support ticket not found.')]
#[Response(status: 422, description: 'Validation error.')]
final class UpdateTicketStatusController extends Controller
{
    public function __construct(
        private readonly UpdateTicketStatusAction $updateTicketStatusAction,
    ) {}

    public function __invoke(SupportTicket $ticket, UpdateTicketStatusRequest $request): JsonResponse
    {
        $this->authorize('update', $ticket);

        $ticket = $this->updateTicketStatusAction->handle($ticket, $request->toData());

        return ApiResponse::success(SupportTicketData::fromModel($ticket));
    }
}
