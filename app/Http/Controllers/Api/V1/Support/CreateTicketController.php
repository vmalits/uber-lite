<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Support;

use App\Actions\Support\CreateTicketAction;
use App\Data\Support\SupportTicketData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Support\CreateTicketRequest;
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
#[Response(status: 201, description: 'Support ticket created successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 422, description: 'Validation failed.')]
final class CreateTicketController extends Controller
{
    public function __construct(
        private readonly CreateTicketAction $createTicketAction,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(CreateTicketRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $this->authorize('create', SupportTicket::class);

        $ticket = $this->createTicketAction->handle($user, $request->toData());

        return ApiResponse::created(
            SupportTicketData::fromModel($ticket),
            __('messages.support.ticket_created'),
        );
    }
}
