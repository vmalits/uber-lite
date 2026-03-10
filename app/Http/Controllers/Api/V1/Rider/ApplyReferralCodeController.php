<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\ApplyReferralCodeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\ApplyReferralCodeRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Apply Referral Code', 'Apply a referral code to get credits')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Referral code applied successfully.')]
final class ApplyReferralCodeController extends Controller
{
    public function __construct(
        private readonly ApplyReferralCodeAction $applyReferralCode,
    ) {}

    public function __invoke(ApplyReferralCodeRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->applyReferralCode->handle(
            user: $user,
            referralCode: $request->getReferralCode(),
        );

        return ApiResponse::success(
            message: __('messages.promo.referral_applied'),
        );
    }
}
