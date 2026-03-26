<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Admin\AnnouncementData;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Queries\Admin\GetAnnouncementsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Get Announcements', 'Get list of all announcements')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetAnnouncementsController extends Controller
{
    public function __construct(
        private readonly GetAnnouncementsQueryInterface $query,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Announcement::class);

        $announcements = $this->query->execute(PaginationHelper::perPage($request));

        return ApiResponse::success(
            data: AnnouncementData::collect($announcements),
        );
    }
}
