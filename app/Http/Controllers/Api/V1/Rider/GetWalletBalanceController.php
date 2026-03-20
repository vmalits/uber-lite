<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\WalletBalanceData;
use App\Enums\Currency;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Rider\GetWalletBalanceQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Wallet Balance', 'Get current wallet balance and pending top-ups count')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Wallet balance retrieved successfully.')]
final class GetWalletBalanceController extends Controller
{
    public function __construct(
        private readonly GetWalletBalanceQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
    ): JsonResponse {
        $pendingCount = $this->query->execute($user);

        $balanceData = new WalletBalanceData(
            balance: $user->credits_balance,
            currency: Currency::MDL,
            pending_count: $pendingCount,
        );

        return ApiResponse::success($balanceData);
    }
}
