<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\DeletePromoCodeAction;
use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class DeletePromoCodeController extends Controller
{
    public function __construct(
        private readonly DeletePromoCodeAction $deletePromoCode,
    ) {}

    public function __invoke(PromoCode $promoCode): JsonResponse
    {
        $this->authorize('delete', $promoCode);

        $this->deletePromoCode->handle($promoCode);

        return ApiResponse::success(
            message: __('messages.promo.deleted'),
        );
    }
}
