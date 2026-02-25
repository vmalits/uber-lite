<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\CreatePromoCodeAction;
use App\Data\Admin\CreatePromoCodeData;
use App\Data\Admin\PromoCodeData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\PromoCodeRequest;
use App\Models\PromoCode;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Promo code created successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class CreatePromoCodeController extends Controller
{
    public function __construct(
        private readonly CreatePromoCodeAction $createPromoCode,
    ) {}

    public function __invoke(PromoCodeRequest $request): JsonResponse
    {
        $this->authorize('create', PromoCode::class);

        $promoCode = $this->createPromoCode->handle(
            data: CreatePromoCodeData::from($request->validated()),
        );

        return ApiResponse::created(
            data: PromoCodeData::fromModel($promoCode),
            message: __('messages.promo.created'),
        );
    }
}
