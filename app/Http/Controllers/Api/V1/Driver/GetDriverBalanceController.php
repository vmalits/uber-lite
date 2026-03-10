<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverPayout;
use App\Models\User;
use App\Queries\Driver\GetDriverBalanceQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Endpoint('Get Balance', 'Get driver\'s current balance for payouts')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Balance retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetDriverBalanceController extends Controller
{
    public function __construct(
        private readonly GetDriverBalanceQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
    ): JsonResponse {
        $this->authorize('viewBalance', DriverPayout::class);

        return ApiResponse::success(
            $this->query->execute($user),
        );
    }
}
