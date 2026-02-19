<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Driver\GetCurrentLocationQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Driver current location retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
final class GetCurrentLocationController extends Controller
{
    public function __construct(
        private readonly GetCurrentLocationQueryInterface $getCurrentLocationQuery,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
    ): JsonResponse {
        $location = $this->getCurrentLocationQuery->execute($user->id);

        if ($location === null) {
            return ApiResponse::success();
        }

        return ApiResponse::success(data: $location);
    }
}
