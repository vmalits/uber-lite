<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\CreateOtpCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\OtpCodeRequest;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Http\JsonResponse;

/**
 * @group Auth
 *
 * Request OTP Code
 *
 * This endpoint generates a one-time password (OTP) for the given phone number
 * and sends it via SMS.
 *
 * @bodyParam phone string required The phone number in E.164 format. Example: +37360000000
 *
 * @response 200 {
 *   "message": "OTP has been requested successfully.",
 *   "data": {
 *     "phone": "+37360000000",
 *     "expires_at": "2025-12-11T23:23:00+00:00"
 *   }
 * }
 */
class RequestOtpController extends Controller
{
    public function __construct(
        private readonly CreateOtpCode $createOtpCode,
        private readonly SmsServiceInterface $smsService,
    ) {}

    public function __invoke(OtpCodeRequest $request): JsonResponse
    {
        $phone = $request->string('phone')->toString();
        $otpCode = $this->createOtpCode->handle($phone);

        $this->smsService->send($phone, "Your OTP code is {$otpCode->code}");

        return response()->json([
            'message' => 'OTP has been requested successfully.',
            'data'    => [
                'phone'      => $otpCode->phone,
                'expires_at' => $otpCode->expires_at->toAtomString(),
            ],
        ]);
    }
}
