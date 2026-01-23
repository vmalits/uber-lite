<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Data\Admin\DriverBanData;
use App\Http\Controllers\Controller;
use App\Models\DriverBan;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ban',
    type: 'string',
    description: 'ULID of the driver ban.',
    required: true,
    example: '01HZY2K8J8QK8Z8Z8Z8Z8Z8Z8Z',
)]
#[Response(status: 200, description: 'Driver ban details retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden.')]
#[Response(status: 404, description: 'Driver ban not found.')]
final class GetBanController extends Controller
{
    public function __invoke(DriverBan $ban): JsonResponse
    {
        $this->authorize('view', $ban);

        return ApiResponse::success(DriverBanData::fromModel($ban));
    }
}
