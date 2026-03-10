<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\RevenueAnalyticsRequest;
use App\Models\User;
use App\Queries\Admin\GetRevenueAnalyticsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Get Revenue Analytics', 'Get revenue analytics and statistics')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('days', 'int', 'Number of days to include (1-365)', example: 30)]
#[Response([
    'success' => true,
    'data'    => [
        'total_revenue'         => 5000000,
        'today_revenue'         => 25000,
        'week_revenue'          => 150000,
        'month_revenue'         => 500000,
        'average_daily_revenue' => 21450.50,
        'average_ride_price'    => 150.25,
        'total_discounts'       => 50000,
        'growth_rate'           => 12.5,
        'daily_revenue'         => [
            ['date' => '2026-03-01', 'revenue' => 25000, 'rides' => 150],
        ],
        'monthly_revenue' => [
            ['month' => '2026-02', 'revenue' => 450000, 'rides' => 3000],
        ],
    ],
], status: 200)]
#[Response(status: 401, description: 'Unauthorized')]
#[Response(status: 403, description: 'Forbidden - not an admin')]
final class GetRevenueAnalyticsController extends Controller
{
    public function __construct(
        private readonly GetRevenueAnalyticsQueryInterface $query,
    ) {}

    public function __invoke(RevenueAnalyticsRequest $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $data = $this->query->execute($request->days());

        return ApiResponse::success($data);
    }
}
