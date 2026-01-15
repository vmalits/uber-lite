<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\AddEmail;
use App\Actions\Auth\ResolveNextAction;
use App\Data\Auth\AddEmailResponse;
use App\Enums\ProfileStep;
use App\Events\Auth\EmailAdded;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\AddEmailRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Throwable;

#[Group('Auth')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Email added successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
class AddEmailController extends Controller
{
    public function __construct(
        private readonly AddEmail $addEmail,
        private readonly EventsDispatcher $events,
        private readonly ResolveNextAction $resolveNextAction,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(AddEmailRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        /** @var User $user */
        $user = $request->user();

        $ok = $this->addEmail->handle($user, $dto->email);
        if (! $ok) {
            return ApiResponse::validationError([
                'phone' => [__('messages.auth.phone_not_verified')],
            ]);
        }

        $this->events->dispatch(new EmailAdded(userId: $user->id, email: $dto->email));

        $next = $this->resolveNextAction->handle($user);

        return ApiResponse::success(
            data: AddEmailResponse::of(
                email: $dto->email,
                profileStep: ProfileStep::EMAIL_ADDED,
            ),
            message: __('messages.auth.email_added'),
            meta: ['next_action' => $next->value],
        );
    }
}
