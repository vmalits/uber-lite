<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\CreateAnnouncementAction;
use App\Data\Admin\AnnouncementData;
use App\Data\Admin\CreateAnnouncementData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\AnnouncementRequest;
use App\Models\Announcement;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Create Announcement', 'Create a new announcement')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Announcement created successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class CreateAnnouncementController extends Controller
{
    public function __construct(
        private readonly CreateAnnouncementAction $createAnnouncement,
    ) {}

    public function __invoke(AnnouncementRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $this->authorize('create', Announcement::class);

        $announcement = $this->createAnnouncement->handle(
            data: CreateAnnouncementData::from($request->validated()),
            adminId: $user->id,
        );

        return ApiResponse::created(
            data: AnnouncementData::fromModel($announcement),
            message: __('messages.announcement.created'),
        );
    }
}
