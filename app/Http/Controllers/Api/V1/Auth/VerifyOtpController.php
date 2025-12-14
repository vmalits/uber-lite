<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\VerifyOtpCode;
use App\Enums\ProfileStep;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\VerifyOtpRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * @group Auth
 *
 * Verify OTP Code
 *
 * This endpoint verifies the one-time password (OTP) for the given phone number.
 *
 * @bodyParam phone string required The phone number in E.164 format. Example: +37360000000
 * @bodyParam code string required The 6-digit OTP code. Example: 123456
 *
 * @response 200 {
 *   "message": "OTP verified successfully.",
 *   "data": {
 *     "phone": "+37360000000",
 *     "profile_step": "phone_verified"
 *   }
 * }
 * @response 422 {"message":"The given data was invalid.","errors":{"code":["Invalid or expired code."]}}
 */
class VerifyOtpController extends Controller
{
    public function __construct(
        private readonly VerifyOtpCode $verifyOtpCode,
    ) {}

    public function __invoke(VerifyOtpRequest $request): JsonResponse
    {
        $phone = $request->string('phone')->toString();
        $code = $request->string('code')->toString();

        $ok = $this->verifyOtpCode->handle($phone, $code);
        if (! $ok) {
            return ApiResponse::validationError([
                'code' => ['Invalid or expired code.'],
            ]);
        }

        return ApiResponse::success([
            'phone'        => $phone,
            'profile_step' => ProfileStep::PHONE_VERIFIED->value,
        ], message: 'OTP verified successfully.');
    }
}
