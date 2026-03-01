<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Driver\MonthlyEarningsRequest;
use App\Models\User;
use App\Queries\Driver\GetMonthlyEarningsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetMonthlyEarningsController extends Controller
{
    public function __construct(
        private readonly GetMonthlyEarningsQueryInterface $query,
    ) {}

    public function __invoke(MonthlyEarningsRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $data = $this->query->execute($user, $request->months());

        return ApiResponse::success($data);
    }
}
