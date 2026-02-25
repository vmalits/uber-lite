<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Admin\PromoCodeData;
use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Queries\Admin\GetPromoCodesQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetPromoCodesController extends Controller
{
    public function __construct(
        private readonly GetPromoCodesQueryInterface $query,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', PromoCode::class);

        $perPage = $request->integer('per_page', 15);

        $promoCodes = $this->query->execute($perPage);

        return ApiResponse::success(
            data: PromoCodeData::collect($promoCodes),
        );
    }
}
