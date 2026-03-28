<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Admin\AchievementData;
use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Get Achievement', 'Get details of a specific achievement')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetAchievementController extends Controller
{
    public function __invoke(Achievement $achievement): JsonResponse
    {
        $this->authorize('view', $achievement);

        return ApiResponse::success(
            data: AchievementData::fromModel($achievement),
        );
    }
}
