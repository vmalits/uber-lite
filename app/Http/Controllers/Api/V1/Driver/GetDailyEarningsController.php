<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Driver\GetDailyEarningsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Daily Earnings', 'Get driver\'s earnings for today')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Daily earnings retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetDailyEarningsController extends Controller
{
    public function __construct(
        private readonly GetDailyEarningsQueryInterface $getDailyEarningsQuery,
    ) {}

    #[QueryParam(
        name: 'from',
        type: 'string',
        description: 'Start date (Y-m-d). Defaults to 30 days ago.',
        required: false,
        example: '2026-01-01',
    )]
    #[QueryParam(
        name: 'to',
        type: 'string',
        description: 'End date (Y-m-d). Defaults to today.',
        required: false,
        example: '2026-01-31',
    )]
    public function __invoke(
        #[CurrentUser] User $user,
        Request $request,
    ): JsonResponse {
        $from = $request->query('from');
        $to = $request->query('to');

        $earnings = $this->getDailyEarningsQuery->execute($user, $from, $to);

        return ApiResponse::success(
            data: $earnings,
        );
    }
}
