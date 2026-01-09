<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Driver;

use App\Actions\User\UploadAvatar;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UploadAvatarRequest;
use App\Models\User;
use App\Services\AvatarStorageService;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Driver')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Avatar uploaded successfully.')]
final class UploadAvatarController extends Controller
{
    public function __construct(
        private readonly UploadAvatar $uploadAvatar,
        private readonly AvatarStorageService $storageService,
    ) {}

    public function __invoke(UploadAvatarRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $path = $this->uploadAvatar->handle(
            $user,
            $request->toUploadAvatarData(),
        );

        return ApiResponse::success(
            data: [
                'avatar_url' => $this->storageService->url($path),
            ],
            message: 'Avatar uploaded successfully.',
        );
    }
}
