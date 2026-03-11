<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Admin\AdminPayoutData;
use App\Http\Controllers\Controller;
use App\Models\DriverPayout;
use App\Queries\Admin\GetPayoutQueryInterface;
use App\Services\Avatar\AvatarUrlService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Admin')]
#[Endpoint('Get Payout', 'Get a single payout details')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(name: 'payout', type: 'string', description: 'ULID of the payout.', required: true)]
#[Response(status: 200, description: 'Payout details retrieved successfully.')]
#[Response(status: 404, description: 'Payout not found.')]
final class GetPayoutController extends Controller
{
    public function __construct(
        private readonly GetPayoutQueryInterface $query,
        private readonly AvatarUrlService $avatarResolver,
    ) {}

    public function __invoke(DriverPayout $payout): JsonResponse
    {
        $this->authorize('viewAdmin', $payout);

        $payout = $this->query->execute($payout->id);

        return ApiResponse::success(
            AdminPayoutData::fromModel($payout, $this->avatarResolver),
        );
    }
}
