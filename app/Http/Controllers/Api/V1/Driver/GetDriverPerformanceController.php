<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\GetDriverPerformanceRequest;
use App\Models\User;
use App\Queries\Driver\GetDriverPerformanceQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Endpoint(title: 'Driver Performance', description: 'Get driver performance metrics with comparison')]
#[QueryParam(
    name: 'period',
    type: 'string',
    description: 'Time period (current_month, last_month, all_time)',
    required: false,
    example: 'current_month',
)]
#[Response(status: 200, description: 'Performance data retrieved successfully')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetDriverPerformanceController extends Controller
{
    public function __construct(
        private readonly GetDriverPerformanceQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        GetDriverPerformanceRequest $request,
    ): JsonResponse {
        $performance = $this->query->execute($user, $request->period());

        return ApiResponse::success($performance);
    }
}
