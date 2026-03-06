<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\RidesAnalyticsRequest;
use App\Models\User;
use App\Queries\Admin\GetRidesAnalyticsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[QueryParam('days', 'int', 'Number of days to include (1-365)', example: 30)]
#[Response([
    'success' => true,
    'data'    => [
        'total_rides'           => 1000,
        'completed_rides'       => 850,
        'cancelled_rides'       => 150,
        'average_ride_price'    => 150.50,
        'average_ride_distance' => 8.5,
        'average_ride_duration' => 15.2,
        'cancellation_rate'     => 15.0,
        'daily_stats'           => [
            [
                'date'            => '2026-03-01',
                'total_rides'     => 50,
                'completed_rides' => 45,
                'cancelled_rides' => 5,
                'revenue'         => 6750,
            ],
        ],
    ],
], status: 200)]
#[Response(status: 401, description: 'Unauthorized')]
#[Response(status: 403, description: 'Forbidden - not an admin')]
final class GetRidesAnalyticsController extends Controller
{
    public function __construct(
        private readonly GetRidesAnalyticsQueryInterface $query,
    ) {}

    public function __invoke(RidesAnalyticsRequest $request): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        $data = $this->query->execute($request->days());

        return ApiResponse::success($data);
    }
}
