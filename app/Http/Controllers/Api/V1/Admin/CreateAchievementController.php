<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\CreateAchievementAction;
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
#[Endpoint('Create Achievement', 'Create a new achievement')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Achievement created successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class CreateAchievementController extends Controller
{
    public function __construct(
        private readonly CreateAchievementAction $createAchievement,
    ) {}

    public function __invoke(AchievementRequest $request): JsonResponse
    {
        $this->authorize('create', Achievement::class);

        $achievement = $this->createAchievement->handle(
            data: CreateAchievementData::from($request->validated()),
        );

        return ApiResponse::created(
            data: AchievementData::fromModel($achievement),
            message: __('messages.achievement.created'),
        );
    }
}
