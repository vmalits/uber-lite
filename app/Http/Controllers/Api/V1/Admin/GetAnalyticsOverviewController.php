<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Admin\GetAnalyticsOverviewQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Get Analytics Overview', 'Get overall platform analytics summary')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response([
    'success' => true,
    'data'    => [
        'daily_active_users'   => 150,
        'monthly_active_users' => 2500,
        'total_revenue'        => 500000,
        'today_revenue'        => 15000,
        'total_rides'          => 10000,
        'today_rides'          => 150,
        'total_users'          => 5000,
        'total_drivers'        => 200,
    ],
], status: 200)]
#[Response(status: 401, description: 'Unauthorized')]
#[Response(status: 403, description: 'Forbidden - not an admin')]
final class GetAnalyticsOverviewController extends Controller
{
    public function __construct(
        private readonly GetAnalyticsOverviewQueryInterface $query,
    ) {}

    public function __invoke(): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $data = $this->query->execute();

        return ApiResponse::success($data);
    }
}
