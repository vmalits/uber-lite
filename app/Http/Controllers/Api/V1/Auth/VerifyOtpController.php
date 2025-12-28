<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\ResolveNextAction;
use App\Actions\Auth\VerifyOtpCode;
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
class VerifyOtpController extends Controller
{
    public function __construct(
        private readonly VerifyOtpCode $verifyOtpCode,
        private readonly ResolveNextAction $resolveNextAction,
    ) {}

    public function __invoke(VerifyOtpRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        $user = $this->verifyOtpCode->handle($dto->phone, $dto->code);
        if ($user === null) {
            return ApiResponse::validationError([
                'code' => ['Invalid or expired code.'],
            ]);
        }

        $token = $user->createToken('auth')->plainTextToken;
        $nextAction = $this->resolveNextAction->handle($user)->value;

        return ApiResponse::success(
            data: VerifyOtpResponse::of(
                profileStep: $user->profile_step ?? ProfileStep::PHONE_VERIFIED,
                token: $token,
            ),
            message: 'OTP verified successfully.',
            meta: ['next_action' => $nextAction]);
    }
}
