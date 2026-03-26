<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\DeleteAnnouncementAction;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Delete Announcement', 'Soft delete an announcement')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class DeleteAnnouncementController extends Controller
{
    public function __construct(
        private readonly DeleteAnnouncementAction $deleteAnnouncement,
    ) {}

    public function __invoke(Announcement $announcement): JsonResponse
    {
        $this->authorize('delete', $announcement);

        $this->deleteAnnouncement->handle($announcement);

        return ApiResponse::success(
            message: __('messages.announcement.deleted'),
        );
    }
}
