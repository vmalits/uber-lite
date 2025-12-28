<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\ResolveNextAction;
use App\Actions\Auth\SelectUserRole;
use App\Data\Auth\SelectRoleResponse;
use App\Enums\ProfileStep;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\SelectRoleRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Throwable;

#[Group('Auth')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Role selected successfully.')]
final class SelectRoleController extends Controller
{
    public function __construct(
        private readonly ResolveNextAction $resolveNextAction,
        private readonly SelectUserRole $selectUserRole,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(SelectRoleRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        /** @var User $user */
        $user = $request->user();

        $user = $this->selectUserRole->handle($user, $dto->role);
        $next = $this->resolveNextAction->handle($user)->value;

        return ApiResponse::success(
            SelectRoleResponse::of(
                role: $dto->role,
                profileStep: ($user->profile_step) ?? ProfileStep::PHONE_VERIFIED,
            ),
            message: 'Role selected successfully.',
            meta: [
                'next_action' => $next,
            ],
        );
    }
}
