<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Support;

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
use Throwable;

#[Group('Support')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Support ticket comment created successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Support ticket not found.')]
#[Response(status: 422, description: 'Validation failed.')]
final class CreateTicketCommentController extends Controller
{
    public function __construct(
        private readonly CreateTicketCommentAction $createTicketCommentAction,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(
        CreateTicketCommentRequest $request,
        #[CurrentUser] User $user,
        SupportTicket $ticket,
    ): JsonResponse {
        $this->authorize('view', $ticket);

        $comment = $this->createTicketCommentAction->handle(
            $user,
            $ticket,
            $request->toData(),
        );

        return ApiResponse::created(
            SupportTicketCommentData::fromModel($comment),
            __('messages.support.comment_created'),
        );
    }
}
