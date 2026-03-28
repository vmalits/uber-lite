<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Admin\AchievementData;
use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Queries\Admin\GetAchievementsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Get Achievements', 'Get a paginated list of all achievements')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetAchievementsController extends Controller
{
    public function __construct(
        private readonly GetAchievementsQueryInterface $query,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Achievement::class);

        $perPage = $request->integer('per_page', 15);

        $achievements = $this->query->execute($perPage);

        return ApiResponse::success(
            data: AchievementData::collect($achievements),
        );
    }
}
