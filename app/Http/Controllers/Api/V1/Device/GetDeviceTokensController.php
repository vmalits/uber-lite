<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Device;

use App\Data\Device\DeviceTokenData;
use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\User;
use App\Queries\Device\GetDeviceTokensQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Device Tokens')]
#[Endpoint('List Devices', 'List registered device tokens for the current user')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetDeviceTokensController extends Controller
{
    public function __construct(
        private readonly GetDeviceTokensQueryInterface $query,
    ) {}

    public function __invoke(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $this->authorize('viewAny', DeviceToken::class);

        $deviceTokens = $this->query->execute(
            userId: $user->id,
            perPage: PaginationHelper::perPage($request),
        );

        return ApiResponse::success(
            data: DeviceTokenData::collect($deviceTokens),
        );
    }
}
