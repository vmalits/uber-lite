<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\AddEmail;
use App\Enums\ProfileStep;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\AddEmailRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

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
    public function __construct(private readonly AddEmail $addEmail) {}

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

        return ApiResponse::success([
            'phone'        => $phone,
            'email'        => $email,
            'profile_step' => ProfileStep::EMAIL_ADDED->value,
        ], message: 'Email added successfully.');
    }
}
