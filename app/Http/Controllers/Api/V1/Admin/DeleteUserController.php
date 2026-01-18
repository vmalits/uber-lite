<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 204, description: 'User deleted successfully.')]
#[Response(status: 401, description: 'Unauthorized.')]
#[Response(status: 403, description: 'Forbidden - not an admin or trying to delete self.')]
#[Response(status: 404, description: 'User not found.')]
final class DeleteUserController extends Controller
{
    public function __invoke(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return ApiResponse::success(data: null, status: 204);
    }
}
