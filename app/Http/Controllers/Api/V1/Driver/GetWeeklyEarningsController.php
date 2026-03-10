<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\WeeklyEarningsRequest;
use App\Models\User;
use App\Queries\Driver\GetWeeklyEarningsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Driver')]
#[Endpoint('Get Weekly Earnings', 'Get driver\'s earnings for current week')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetWeeklyEarningsController extends Controller
{
    public function __construct(
        private readonly GetWeeklyEarningsQueryInterface $query,
    ) {}

    public function __invoke(WeeklyEarningsRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $data = $this->query->execute($user, $request->weeks());

        return ApiResponse::success($data);
    }
}
