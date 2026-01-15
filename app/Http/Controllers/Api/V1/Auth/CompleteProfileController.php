<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CompleteProfile;
use App\Data\Auth\CompleteProfileResponse;
use App\Enums\NextAction;
use App\Events\Auth\ProfileCompleted;
use App\Exceptions\Auth\ProfileNotVerifiedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\CompleteProfileRequest;
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
#[Response(status: 200, description: 'Profile completed successfully.')]
#[Response(status: 422, description: 'Validation errors or email not verified.')]
final class CompleteProfileController extends Controller
{
    public function __construct(
        private readonly CompleteProfile $completeProfile,
        private readonly EventsDispatcher $events,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(CompleteProfileRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        /** @var User $user */
        $user = $request->user();

        try {
            $user = $this->completeProfile->handle($user, $dto->first_name, $dto->last_name);
        } catch (ProfileNotVerifiedException $e) {
            return ApiResponse::validationError([
                'email' => [$e->getMessage()],
            ]);
        }

        $this->events->dispatch(new ProfileCompleted(
            userId: $user->id,
            firstName: $dto->first_name,
            lastName: $dto->last_name,
        ));

        return ApiResponse::success(
            data: CompleteProfileResponse::fromUser($user),
            message: __('messages.auth.profile_completed'),
            meta: ['next_action' => NextAction::DONE->value],
        );
    }
}
