<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\User\UserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Admin\GetUserQueryInterface;
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
        private readonly GetUserQueryInterface $query,
        private readonly AvatarUrlService $avatarResolver,
    ) {}

    public function __invoke(string $user): JsonResponse
    {
        $userData = UserData::fromModel($this->query->execute($user), $this->avatarResolver);

        $this->authorize('view', User::findOrFail($user));

        return ApiResponse::success($userData);
    }
}
