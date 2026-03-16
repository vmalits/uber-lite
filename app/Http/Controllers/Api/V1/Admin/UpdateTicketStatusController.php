<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Support\UpdateTicketStatusAction;
use App\Data\Support\SupportTicketData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\UpdateTicketStatusRequest;
use App\Models\SupportTicket;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Admin')]
#[Endpoint('Update Ticket Status', 'Update the status of a support ticket')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ticket',
    type: 'string',
    description: 'ULID of the support ticket.',
    required: true,
    example: '01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z',
)]
#[Response(status: 200, description: 'Ticket status updated successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Support ticket not found.')]
#[Response(status: 422, description: 'Validation errors.')]
final class UpdateTicketStatusController extends Controller
{
    public function __construct(
        private readonly UpdateTicketStatusAction $updateTicketStatus,
    ) {}

    public function __invoke(
        SupportTicket $ticket,
        UpdateTicketStatusRequest $request,
    ): JsonResponse {
        $this->authorize('update', $ticket);

        $ticket = $this->updateTicketStatus->handle($ticket, $request->toData());

        return ApiResponse::success(
            data: SupportTicketData::fromModel($ticket),
        );
    }
}
