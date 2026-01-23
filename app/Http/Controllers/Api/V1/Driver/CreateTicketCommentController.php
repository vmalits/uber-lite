<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Support\CreateTicketCommentAction;
use App\Data\Support\SupportTicketCommentData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Support\CreateTicketCommentRequest;
use App\Models\SupportTicket;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;
use Throwable;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ticket',
    type: 'string',
    description: 'ULID of the support ticket.',
    required: true,
    example: '01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z',
)]
#[Response(status: 201, description: 'Support ticket comment created successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Support ticket not found.')]
#[Response(status: 422, description: 'Validation errors.')]
final class CreateTicketCommentController extends Controller
{
    public function __construct(
        private readonly CreateTicketCommentAction $createTicketComment,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(
        #[CurrentUser] User $user,
        SupportTicket $ticket,
        CreateTicketCommentRequest $request,
    ): JsonResponse {
        $this->authorize('view', $ticket);

        $comment = $this->createTicketComment->handle($user, $ticket, $request->toData());

        return ApiResponse::created(
            data: SupportTicketCommentData::fromModel($comment),
        );
    }
}
