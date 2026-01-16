<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\ResolveNextActionAction;
use App\Events\Auth\EmailVerificationRequested;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\EmailVerificationNotificationRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Auth')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Verification link sent.')]
#[Response(status: 200, description: 'Email is already verified.')]
final class EmailVerificationNotificationController extends Controller
{
    public function __construct(
        private readonly EventsDispatcher $events,
        private readonly ResolveNextActionAction $resolveNextAction,
    ) {}

    public function __invoke(EmailVerificationNotificationRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            $next = $this->resolveNextAction->handle($user)->value;

            return ApiResponse::success(
                message: __('messages.auth.email_already_verified'),
                meta: ['next_action' => $next],
            );
        }

        $this->events->dispatch(new EmailVerificationRequested(userId: $user->id));

        return ApiResponse::success(
            message: __('messages.auth.verification_link_sent'),
        );
    }
}
