<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\UpdateAnnouncementAction;
use App\Data\Admin\AnnouncementData;
use App\Data\Admin\CreateAnnouncementData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\AnnouncementRequest;
use App\Models\Announcement;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Update Announcement', 'Update an existing announcement')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Announcement updated successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class UpdateAnnouncementController extends Controller
{
    public function __construct(
        private readonly UpdateAnnouncementAction $updateAnnouncement,
    ) {}

    public function __invoke(AnnouncementRequest $request, Announcement $announcement): JsonResponse
    {
        $this->authorize('update', $announcement);

        $announcement = $this->updateAnnouncement->handle(
            announcement: $announcement,
            data: CreateAnnouncementData::from($request->validated()),
        );

        return ApiResponse::success(
            data: AnnouncementData::fromModel($announcement),
            message: __('messages.announcement.updated'),
        );
    }
}
