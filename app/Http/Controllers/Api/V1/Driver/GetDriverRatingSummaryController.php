<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Driver\GetDriverRatingSummaryQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Driver Rating Summary', 'Get rating summary for the authenticated driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Driver rating summary retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetDriverRatingSummaryController extends Controller
{
    public function __construct(
        private readonly GetDriverRatingSummaryQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
    ): JsonResponse {
        $summary = $this->query->execute(
            driverId: $user->id,
        );

        return ApiResponse::success($summary);
    }
}
