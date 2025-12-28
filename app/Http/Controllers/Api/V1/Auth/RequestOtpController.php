<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CreateOtpCode;
use App\Data\Auth\OtpCodeResponse;
use App\Events\Auth\OtpRequested;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\OtpCodeRequest;
use App\Services\OtpService;
use App\Support\ApiResponse;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Random\RandomException;
use Throwable;

#[Group('Auth')]
#[Response(status: 200, description: 'OTP has been requested successfully.')]
#[Response(status: 429, description: 'Too many OTP requests. Please try again later.')]
class RequestOtpController extends Controller
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

        $this->events->dispatch(new OtpRequested(phone: $dto->phone, otpCode: $generatedCode));

        return ApiResponse::success(
            data: OtpCodeResponse::fromModel($otpCode),
            message: 'OTP has been requested successfully.');
    }
}
