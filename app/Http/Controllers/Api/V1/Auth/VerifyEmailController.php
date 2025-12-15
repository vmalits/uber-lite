<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\VerifyEmail as VerifyEmailAction;
use App\Data\Auth\VerifyEmailResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

final class VerifyEmailController extends Controller
{
    public function __construct(private readonly VerifyEmailAction $verifyEmail) {}

    /**
     * @group Auth
     *
     * Verify Email
     *
     * Route formats:
     * - GET /api/v1/auth/email/verify/{user}/{hash}
     *
     * @urlParam user string required The user's ULID.
     * @urlParam hash string required SHA-1 hash of the user's email.
     *
     * @response 200 {
     *   "message": "Email verified successfully.",
     *   "data": {
     *     "user_id": "01HG7Z8J8F6Z5Q9YF5W3ZQ1X2Y",
     *     "email": "verifyme@example.com",
     *     "profile_step": "email_verified",
     *     "verified_at": "2025-12-11T23:23:00+00:00",
     *     "verified": true,
     *     "already_verified": false
     *   }
     * }
     * @response 200 {
     *   "message": "Email already verified.",
     *   "data": {
     *     "user_id": "01HG7Z8J8F6Z5Q9YF5W3ZQ1X2Y",
     *     "email": "verifyme@example.com",
     *     "profile_step": "email_verified",
     *     "verified_at": "2025-12-11T23:23:00+00:00",
     *     "verified": true,
     *     "already_verified": true
     *   }
     * }
     */
    public function __invoke(User $user, string $hash): JsonResponse
    {
        $expectedHash = sha1($user->getEmailForVerification());
        if (! hash_equals($expectedHash, $hash)) {
            return ApiResponse::error('Invalid verification link.', 403);
        }

        if ($user->hasVerifiedEmail()) {
            return ApiResponse::success(
                data: VerifyEmailResponse::fromUser($user, alreadyVerified: true),
                message: 'Email already verified.',
            );
        }

        $this->verifyEmail->handle($user);
        $user->refresh();

        return ApiResponse::success(
            data: VerifyEmailResponse::fromUser($user),
            message: 'Email verified successfully.',
        );
    }
}
