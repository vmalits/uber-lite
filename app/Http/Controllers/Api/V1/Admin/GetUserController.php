<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\User\UserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Avatar\AvatarUrlService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'User details retrieved successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin.')]
#[Response(status: 404, description: 'User not found.')]
final class GetUserController extends Controller
{
    public function __construct(
        private readonly AvatarUrlService $avatarResolver,
    ) {}

    public function __invoke(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return ApiResponse::success(
            UserData::fromModel($user, $this->avatarResolver),
        );
    }
}
