<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\ResolveNextActionAction;
use App\Actions\Auth\TrackOtpVerificationAttemptAction;
use App\Actions\Auth\VerifyOtpCodeAction;
use App\Data\Auth\VerifyOtpResponse;
use App\Enums\ProfileStep;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\VerifyOtpRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;

#[Group('Auth')]
#[Response(status: 200, description: 'OTP verified successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
#[Response(status: 429, description: 'Too many failed attempts. Account temporarily locked.')]
class VerifyOtpController extends Controller
{
    public function __construct(
        private readonly VerifyOtpCodeAction $verifyOtpCode,
        private readonly ResolveNextActionAction $resolveNextAction,
        private readonly TrackOtpVerificationAttemptAction $trackOtpVerificationAttempt,
    ) {}

    public function __invoke(VerifyOtpRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        if ($this->trackOtpVerificationAttempt->isBlocked($dto->phone)) {
            $seconds = $this->trackOtpVerificationAttempt->getBlockRemainingSeconds($dto->phone);

            return ApiResponse::tooManyRequests(
                __('messages.auth.too_many_attempts'),
                $seconds,
            );
        }

        $user = $this->verifyOtpCode->handle($dto->phone, $dto->code);
        if ($user === null) {
            $this->trackOtpVerificationAttempt->trackFailedAttempt($dto->phone);

            return ApiResponse::validationError([
                'code' => [__('messages.auth.invalid_or_expired_code')],
            ]);
        }

        $this->trackOtpVerificationAttempt->resetFailedAttempts($dto->phone);

        $token = $user->createToken('auth')->plainTextToken;
        $nextAction = $this->resolveNextAction->handle($user)->value;

        return ApiResponse::success(
            data: VerifyOtpResponse::of(
                profileStep: $user->profile_step ?? ProfileStep::PHONE_VERIFIED,
                token: $token,
            ),
            message: __('messages.auth.verification_successful'),
            meta: ['next_action' => $nextAction]);
    }
}
