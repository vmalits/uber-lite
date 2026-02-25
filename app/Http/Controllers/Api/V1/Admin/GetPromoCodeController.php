<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Admin\PromoCodeData;
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
final class GetPromoCodeController extends Controller
{
    public function __invoke(PromoCode $promoCode): JsonResponse
    {
        $this->authorize('view', $promoCode);

        return ApiResponse::success(
            data: PromoCodeData::fromModel($promoCode),
        );
    }
}
