<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\UpdatePromoCodeAction;
use App\Data\Admin\CreatePromoCodeData;
use App\Data\Admin\PromoCodeData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\PromoCodeRequest;
use App\Models\PromoCode;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Update Promo Code', 'Update an existing promotional code')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Promo code updated successfully.')]
final class UpdatePromoCodeController extends Controller
{
    public function __construct(
        private readonly UpdatePromoCodeAction $updatePromoCode,
    ) {}

    public function __invoke(PromoCodeRequest $request, PromoCode $promoCode): JsonResponse
    {
        $this->authorize('update', $promoCode);

        $promoCode = $this->updatePromoCode->handle(
            promoCode: $promoCode,
            data: CreatePromoCodeData::from($request->validated()),
        );

        return ApiResponse::success(
            data: PromoCodeData::fromModel($promoCode),
            message: __('messages.promo.updated'),
        );
    }
}
