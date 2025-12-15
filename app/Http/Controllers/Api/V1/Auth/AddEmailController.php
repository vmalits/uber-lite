<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\AddEmail;
use App\Data\Auth\AddEmailResponse;
use App\Enums\ProfileStep;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\AddEmailRequest;
use App\Queries\Auth\FindUserByPhoneQuery;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * @group Auth
 *
 * Add Email
 *
 * Attach an email to the user identified by phone. Phone must be verified first.
 *
 * @bodyParam phone string required The phone number in E.164 format. Example: +37360000000
 * @bodyParam email string required A valid email address. Example: user@gmail.com
 *
 * @response 200 {
 *   "message": "Email added successfully.",
 *   "data": {
 *     "phone": "+37360000000",
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
        private readonly FindUserByPhoneQuery $findUserByPhone,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(AddEmailRequest $request): JsonResponse
    {
        $phone = $request->string('phone')->toString();
        $email = $request->string('email')->toString();

        $ok = $this->addEmail->handle($phone, $email);
        if (! $ok) {
            return ApiResponse::validationError([
                'phone' => ['Phone is not verified.'],
            ]);
        }

        $user = $this->findUserByPhone->execute($phone);
        $user?->sendEmailVerificationNotification();

        return ApiResponse::success(
            AddEmailResponse::of(
                phone: $phone,
                email: $email,
                profileStep: ProfileStep::EMAIL_ADDED,
            ),
            message: 'Email added successfully.',
        );
    }
}
