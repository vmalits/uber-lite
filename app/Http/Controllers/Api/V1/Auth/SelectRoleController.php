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
use Throwable;

/**
 * @group Auth
 *
 * Select Role
 *
 * Choose a role for the authenticated user: rider or driver.
 *
 * Requires Bearer token (issued after OTP verification).
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @response 200 {
 *   "message": "Role selected successfully.",
 *   "data": {
 *     "role": "rider",
 *     "profile_step": "phone_verified"
 *   },
 *   "meta": {"next_action": "add_email"}
 * }
 */
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
