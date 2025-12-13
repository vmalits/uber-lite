<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CreateOtpCode;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\OtpCodeRequest;
use App\Jobs\Auth\SendOtpSmsJob;
use App\Services\OtpService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
        private readonly OtpService $otpService,
        private readonly CreateOtpCode $createOtpCode,
    ) {}

    public function __invoke(OtpCodeRequest $request): JsonResponse
    {
        $phone = $request->string('phone')->toString();
        $generatedCode = $this->otpService->generateOtpCode();
        $otpCode = $this->createOtpCode->handle($phone, $generatedCode);

        SendOtpSmsJob::dispatch($phone, $generatedCode);

        return ApiResponse::success([
            'phone'      => $otpCode->phone,
            'expires_at' => $otpCode->expires_at->toAtomString(),
        ], message: 'OTP has been requested successfully.');
    }
}
