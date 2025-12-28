<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\Driver\Start;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;
use Throwable;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ride',
    type: 'string',
    description: 'ULID of the ride.',
    required: true,
    example: '01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z',
)]
#[Response(status: 200, description: 'Ride has started.')]
final class StartController extends Controller
{
    public function __construct(
        private readonly Start $start,
    ) {}

    /**
     * @throws Throwable
     */
    public function __invoke(#[CurrentUser] User $user, Ride $ride): JsonResponse
    {
        $this->authorize('start', $ride);

        $this->start->handle(
            ride: $ride,
            driverId: $user->id,
        );

        return ApiResponse::success(
            message: 'Ride has started.',
        );
    }
}
