<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\RemovePromoCodeAction;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class RemovePromoCodeController extends Controller
{
    public function __construct(
        private readonly RemovePromoCodeAction $removePromoCode,
    ) {}

    public function __invoke(Ride $ride): JsonResponse
    {
        $this->authorize('applyPromoCode', $ride);

        /** @var User $user */
        $user = request()->user();

        $this->removePromoCode->handle(
            user: $user,
            ride: $ride,
        );

        return ApiResponse::success(
            message: __('messages.promo.removed'),
        );
    }
}
