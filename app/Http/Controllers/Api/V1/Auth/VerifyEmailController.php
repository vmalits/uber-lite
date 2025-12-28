<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\VerifyEmail as VerifyEmailAction;
use App\Data\Auth\VerifyEmailResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

#[Group('Auth')]
#[Response(status: 200, description: 'Email verified successfully.')]
#[Response(status: 200, description: 'Email already verified.')]
final class VerifyEmailController extends Controller
{
    public function __construct(private readonly VerifyEmailAction $verifyEmail) {}

    public function __invoke(User $user, string $hash): JsonResponse
    {
        $expectedHash = sha1($user->getEmailForVerification());
        if (! hash_equals($expectedHash, $hash)) {
            return ApiResponse::forbidden('Invalid verification link.');
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
