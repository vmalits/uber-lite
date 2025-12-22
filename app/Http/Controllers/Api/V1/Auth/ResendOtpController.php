<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CreateOtpCode;
use App\Data\Auth\OtpCodeResponse;
use App\Events\Auth\OtpResent;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\OtpCodeRequest;
use App\Services\OtpService;
use App\Support\ApiResponse;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Http\JsonResponse;
use Random\RandomException;
use Throwable;

/**
 * @group Auth
 *
 * Resend OTP Code
 *
 * This endpoint resends a one-time password (OTP) for the given phone number
 * by generating a new code and sending it via SMS.
 *
 * Rate limited to 3 requests per 15 minutes per phone number.
 *
 * @response 200 {
 *   "message": "OTP has been resent successfully.",
 *   "data": {
 *     "phone": "+37360000000",
 *     "expires_at": {
 *       "human": "1 minute from now",
 *       "string": "2025-12-11 23:23:00"
 *     }
 *   }
 * }
 * @response 429 {
 *   "message": "Too many OTP requests. Please try again later.",
 *   "retry_after": 900
 * }
 */
class ResendOtpController extends Controller
{
    public function __construct(
        private readonly OtpService $otpService,
        private readonly CreateOtpCode $createOtpCode,
        private readonly EventsDispatcher $events,
    ) {}

    /**
     * @throws Throwable
     * @throws RandomException
     */
    public function __invoke(OtpCodeRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        $generatedCode = $this->otpService->generateOtpCode();
        $otpCode = $this->createOtpCode->handle($dto->phone, $generatedCode);

        $this->events->dispatch(new OtpResent(phone: $dto->phone, otpCode: $generatedCode));

        return ApiResponse::success(
            data: OtpCodeResponse::fromModel($otpCode),
            message: 'OTP has been resent successfully.');
    }
}
