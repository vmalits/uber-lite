<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CreateOtpCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\OtpCodeRequest;
use App\Jobs\Auth\SendOtpSmsJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * @group Auth
 *
 * Request OTP Code
 *
 * This endpoint generates a one-time password (OTP) for the given phone number
 * and sends it via SMS.
 *
 * Rate limited to 3 requests per 15 minutes per phone number.
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
 * @response 429 {
 *   "message": "Too many OTP requests. Please try again later.",
 *   "retry_after": 900
 * }
 */
class RequestOtpController extends Controller
{
    public function __construct(
        private readonly CreateOtpCode $createOtpCode,
    ) {}

    public function __invoke(OtpCodeRequest $request): JsonResponse
    {
        $phone = $request->string('phone')->toString();
        $otpCode = $this->createOtpCode->handle($phone);

        SendOtpSmsJob::dispatch($phone, $otpCode->code);

        return response()->json(
            data: [
                'message' => 'OTP has been requested successfully.',
                'data'    => [
                    'phone'      => $otpCode->phone,
                    'expires_at' => $otpCode->expires_at->toAtomString(),
                ],
            ], status: ResponseAlias::HTTP_OK);
    }
}
