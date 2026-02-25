<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\ApplyPromoCodeAction;
use App\Data\Rider\AppliedPromoCodeData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\ApplyPromoCodeRequest;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Promo code applied successfully.')]
#[Response(status: 422, description: 'Invalid or expired promo code.')]
final class ApplyPromoCodeController extends Controller
{
    public function __construct(
        private readonly ApplyPromoCodeAction $applyPromoCode,
    ) {}

    public function __invoke(ApplyPromoCodeRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('applyPromoCode', $ride);

        /** @var User $user */
        $user = $request->user();

        $ride = $this->applyPromoCode->handle(
            user: $user,
            ride: $ride,
            code: $request->getCode(),
        );

        return ApiResponse::success(
            data: AppliedPromoCodeData::fromRide($ride),
            message: __('messages.promo.applied'),
        );
    }
}
