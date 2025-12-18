<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CompleteProfile;
use App\Data\Auth\CompleteProfileResponse;
use App\Enums\NextAction;
use App\Enums\ProfileStep;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\CompleteProfileRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * @group Auth
 *
 * Complete Profile
 *
 * Submit first and last name to complete the registration after email verification.
 *
 * Requires Bearer token (issued after OTP verification).
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @response 200 {
 *   "message": "Profile completed successfully.",
 *   "data": {
 *     "first_name": "John",
 *     "last_name": "Doe",
 *     "profile_step": "completed"
 *   },
 *   "meta": {"next_action": "done"}
 * }
 * @response 422 {"message":"The given data was invalid.","errors":{"phone":["Email is not verified."]}}
 */
class CompleteProfileController extends Controller
{
    public function __construct(
        private readonly CompleteProfile $completeProfile,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(CompleteProfileRequest $request): JsonResponse
    {
        $firstName = $request->string('first_name')->toString();
        $lastName = $request->string('last_name')->toString();

        /** @var User $user */
        $user = $request->user();

        $ok = $this->completeProfile->handle($user, $firstName, $lastName);
        if (! $ok) {
            return ApiResponse::validationError([
                'email' => ['Email is not verified.'],
            ]);
        }

        return ApiResponse::success(
            data: CompleteProfileResponse::of(
                phone: $user->phone,
                firstName: $firstName,
                lastName: $lastName,
                profileStep: ProfileStep::COMPLETED,
            ),
            message: 'Profile completed successfully.',
            meta: ['next_action' => NextAction::DONE->value],
        );
    }
}
