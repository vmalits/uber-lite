<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\User\UploadAvatar;
use App\Enums\AvatarSize;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\User\UploadAvatarRequest;
use App\Models\User;
use App\Services\Avatar\AvatarUrlService;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Random\RandomException;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Avatar upload processing started.')]
final class UploadAvatarController extends Controller
{
    public function __construct(
        private readonly UploadAvatar $uploadAvatar,
        private readonly AvatarUrlService $avatarUrlService,
    ) {}

    /**
     * @throws RandomException
     */
    public function __invoke(UploadAvatarRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $result = $this->uploadAvatar->handle(
            $user,
            $request->toUploadAvatarData(),
        );

        $urls = [];
        foreach (AvatarSize::cases() as $size) {
            $urls[$size->value] = $this->avatarUrlService->getUrl($user, $size);
        }

        return ApiResponse::success(
            data: [
                'processing' => $result['processing'],
                'sizes'      => $urls,
            ],
            message: 'Avatar upload processing started.',
        );
    }
}
