<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Admin\AdjustUserCreditsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\AdjustCreditsRequest;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Adjust User Credits', 'Add or deduct credits from a user\'s account')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Credits adjusted successfully.')]
final class AdjustUserCreditsController extends Controller
{
    public function __construct(
        private readonly AdjustUserCreditsAction $adjustCredits,
    ) {}

    public function __invoke(AdjustCreditsRequest $request, User $user): JsonResponse
    {
        $this->authorize('adjustCredits', $user);

        $this->adjustCredits->handle(
            user: $user,
            amount: $request->getAmount(),
            description: $request->getDescription(),
        );

        return ApiResponse::success(
            data: [
                'credits_balance' => $user->refresh()->credits_balance,
            ],
            message: __('messages.promo.credits_adjusted'),
        );
    }
}
