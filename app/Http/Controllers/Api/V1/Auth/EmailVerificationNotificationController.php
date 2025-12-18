<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Events\Auth\EmailVerificationRequested;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\EmailVerificationNotificationRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Http\JsonResponse;

/**
 * @group Auth
 *
 * Send Email Verification Notification
 *
 * Sends a verification link to the authenticated user's email address.
 *
 * Requires Bearer token (issued after OTP verification).
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @response 200 {"message":"Verification link sent."}
 */
final class EmailVerificationNotificationController extends Controller
{
    public function __construct(private readonly EventsDispatcher $events) {}

    public function __invoke(EmailVerificationNotificationRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return ApiResponse::success(message: 'Email is already verified.');
        }

        $this->events->dispatch(new EmailVerificationRequested(userId: $user->id));

        return ApiResponse::success(message: 'Verification link sent.');
    }
}
