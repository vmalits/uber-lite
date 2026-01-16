<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Centrifugo;

use App\Actions\Centrifugo\GenerateCentrifugoTokenAction;
use App\Data\Centrifugo\CentrifugoTokenData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('WS')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'JWT token for Centrifugo connection retrieved successfully.')]
final class TokenController extends Controller
{
    public function __construct(
        private readonly GenerateCentrifugoTokenAction $generateToken,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $token = $this->generateToken->handle($user);

        return ApiResponse::success(
            data: new CentrifugoTokenData(token: $token),
        );
    }
}
