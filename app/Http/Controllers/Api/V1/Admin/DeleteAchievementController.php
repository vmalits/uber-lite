<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\DeleteAchievementAction;
use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Delete Achievement', 'Delete an achievement')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class DeleteAchievementController extends Controller
{
    public function __construct(
        private readonly DeleteAchievementAction $deleteAchievement,
    ) {}

    public function __invoke(Achievement $achievement): JsonResponse
    {
        $this->authorize('delete', $achievement);

        $this->deleteAchievement->handle($achievement);

        return ApiResponse::success(
            message: __('messages.achievement.deleted'),
        );
    }
}
