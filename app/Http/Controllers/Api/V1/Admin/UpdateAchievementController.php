<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\UpdateAchievementAction;
use App\Data\Admin\AchievementData;
use App\Data\Admin\CreateAchievementData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\AchievementRequest;
use App\Models\Achievement;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Update Achievement', 'Update an existing achievement')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Achievement updated successfully.')]
final class UpdateAchievementController extends Controller
{
    public function __construct(
        private readonly UpdateAchievementAction $updateAchievement,
    ) {}

    public function __invoke(AchievementRequest $request, Achievement $achievement): JsonResponse
    {
        $this->authorize('update', $achievement);

        $achievement = $this->updateAchievement->handle(
            achievement: $achievement,
            data: CreateAchievementData::from($request->validated()),
        );

        return ApiResponse::success(
            data: AchievementData::fromModel($achievement),
            message: __('messages.achievement.updated'),
        );
    }
}
