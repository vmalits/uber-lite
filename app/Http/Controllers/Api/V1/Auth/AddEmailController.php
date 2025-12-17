<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\AddEmail;
use App\Data\Auth\AddEmailResponse;
use App\Enums\ProfileStep;
use App\Events\Auth\EmailAdded;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\AddEmailRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

/**
 * @group Auth
 *
 * Add Email
 *
 * Attach an email to the authenticated user. Phone must be verified first.
 *
 * Requires Bearer token (issued after OTP verification).
 *
 * @authenticated
 *
 * @header Authorization string required Bearer <token>
 *
 * @bodyParam email string required A valid email address. Example: user@gmail.com
 *
 * @response 200 {
 *   "message": "Email added successfully.",
 *   "data": {
 *     "email": "user@gmail.com",
 *     "profile_step": "email_added"
 *   }
 * }
 * @response 422 {"message":"The given data was invalid.","errors":{"phone":["Phone is not verified."]}}
 */
class AddEmailController extends Controller
{
    public function __construct(
        private readonly AddEmail $addEmail,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(AddEmailRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $email = $request->string('email')->toString();

        $ok = $this->addEmail->handle($user, $email);
        if (! $ok) {
            return ApiResponse::validationError([
                'phone' => ['Phone is not verified.'],
            ]);
        }

        event(new EmailAdded(userId: $user->id, email: $email));

        return ApiResponse::success(
            AddEmailResponse::of(
                email: $email,
                profileStep: ProfileStep::EMAIL_ADDED,
            ),
            message: 'Email added successfully.',
        );
    }
}
