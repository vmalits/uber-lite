<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Announcement;

use App\Data\Admin\AnnouncementData;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Announcement\GetActiveAnnouncementsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;

#[Group('Announcements')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Endpoint('Get Active Announcements', 'Get active announcements filtered by user role')]
#[QueryParam(
    name: 'per_page',
    type: 'int',
    description: 'Number of announcements per page',
    example: '15',
)]
#[Response([
    'success' => true,
    'data'    => [
        'items' => [
            [
                'id'           => '01HQXYZABC1234567890DEFGHI',
                'title'        => 'New Feature Launch',
                'body'         => 'We are excited to announce...',
                'target'       => 'all',
                'is_active'    => true,
                'published_at' => ['human' => '1 hour ago', 'string' => '2026-03-26 12:00:00'],
                'expires_at'   => null,
                'created_at'   => ['human' => '1 hour ago', 'string' => '2026-03-26 12:00:00'],
                'updated_at'   => ['human' => '1 hour ago', 'string' => '2026-03-26 12:00:00'],
            ],
        ],
        'pagination' => [
            'total'        => 1,
            'per_page'     => 15,
            'current_page' => 1,
            'last_page'    => 1,
        ],
    ],
], status: 200)]
final class GetActiveAnnouncementsController extends Controller
{
    public function __construct(
        private readonly GetActiveAnnouncementsQueryInterface $query,
    ) {}

    public function __invoke(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $announcements = $this->query->execute(
            role: $user->role ?? UserRole::RIDER,
            perPage: PaginationHelper::perPage($request),
        );

        return ApiResponse::success(
            data: AnnouncementData::collect($announcements),
        );
    }
}
