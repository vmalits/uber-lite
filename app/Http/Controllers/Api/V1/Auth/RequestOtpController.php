<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CreateOtpCodeAction;
use App\Data\Auth\OtpCodeResponse;
use App\Events\Auth\OtpRequested;
use App\Exceptions\Auth\ActiveOtpCodeAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\OtpCodeRequest;
use App\Services\OtpService;
use App\Support\ApiResponse;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Psr\Log\LoggerInterface;
use Random\RandomException;
use Throwable;

#[Group('Auth')]
#[Response(status: 200, description: 'OTP has been requested successfully.')]
#[Response(status: 429, description: 'Too many OTP requests. Please try again later.')]
#[Response(status: 409, description: 'An active OTP code already exists. Please use the resend endpoint.')]
class RequestOtpController extends Controller
{
    public function __construct(
        private readonly OtpService $otpService,
        private readonly CreateOtpCodeAction $createOtpCode,
        private readonly EventsDispatcher $events,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * @throws Throwable
     * @throws RandomException
     */
    public function __invoke(OtpCodeRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        $this->logger->info('OTP request received', ['phone' => $dto->phone]);

        try {
            $generatedCode = $this->otpService->generateOtpCode();
            $otpCode = $this->createOtpCode->handle($dto->phone, $generatedCode);

            $this->logger->info('OTP code created successfully', ['phone' => $dto->phone, 'otp_id' => $otpCode->id]);

            $this->events->dispatch(new OtpRequested(phone: $dto->phone, otpCode: $generatedCode));

            return ApiResponse::success(
                data: OtpCodeResponse::fromModel($otpCode),
                message: __('messages.auth.otp_requested'));
        } catch (ActiveOtpCodeAlreadyExistsException $e) {
            $this->logger->warning('OTP creation failed - active code already exists', ['phone' => $dto->phone]);

            return ApiResponse::error($e->getMessage(), 409);
        }
    }
}
