<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Admin\DriverBanData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Driver\GetActiveBansQueryInterface;
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
#[Response(status: 200, description: 'Active driver bans retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden. Profile step isn\'t completed.')]
final class GetActiveBansController extends Controller
{
    public function __construct(
        private readonly GetActiveBansQueryInterface $getActiveBansQuery,
    ) {}

    public function __invoke(#[CurrentUser] User $user): JsonResponse
    {
        $bans = $this->getActiveBansQuery->execute($user);

        /** @var array<string, mixed> $data */
        $data = DriverBanData::collect($bans)->toArray();

        return ApiResponse::success($data);
    }
}
