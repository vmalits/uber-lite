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

/**
 * @group Auth
 *
 * Verify OTP Code
 *
 * This endpoint verifies the one-time password (OTP) for the given phone number.
 *
 * @response 200 {
 *   "success": true,
 *   "message": "OTP verified successfully.",
 *   "data": {
 *     "profile_step": "phone_verified",
 *     "token": "<Personal Access Token>",
 *     "token_type": "Bearer"
 *   },
 *   "meta": {
 *     "next_action": "select_role"
 *   }
 * }
 * @response 422 {
 *   "success": false,
 *   "message": "The given data was invalid.",
 *   "errors": {"code": ["Invalid or expired code."]}
 * }
 */
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
