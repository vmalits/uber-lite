<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Admin\GetDriverEarningsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Get Driver Earnings', 'Get earnings breakdown for a specific driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Driver earnings retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
#[Response(status: 404, description: 'Driver not found.')]
final class GetDriverEarningsController extends Controller
{
    public function __construct(
        private readonly GetDriverEarningsQueryInterface $getDriverEarningsQuery,
    ) {}

    #[QueryParam(
        name: 'from',
        type: 'string',
        description: 'Start date for daily breakdown (Y-m-d). Defaults to 30 days ago.',
        required: false,
        example: '2026-01-01',
    )]
    #[QueryParam(
        name: 'to',
        type: 'string',
        description: 'End date for daily breakdown (Y-m-d). Defaults to today.',
        required: false,
        example: '2026-01-31',
    )]
    public function __invoke(Request $request, User $driver): JsonResponse
    {
        $this->authorize('view', $driver);

        $from = $request->query('from');
        $to = $request->query('to');

        $earnings = $this->getDriverEarningsQuery->execute($driver, $from, $to);

        return ApiResponse::success($earnings);
    }
}
