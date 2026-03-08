<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Queries\Admin\GetRealTimeAnalyticsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Real-time analytics data retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
final class GetRealTimeAnalyticsController extends Controller
{
    public function __construct(
        private readonly GetRealTimeAnalyticsQueryInterface $query,
    ) {}

    public function __invoke(): JsonResponse
    {
        $data = $this->query->execute();

        return ApiResponse::success($data->toArray());
    }
}
