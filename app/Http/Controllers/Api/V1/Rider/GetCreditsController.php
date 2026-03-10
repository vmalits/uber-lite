<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\CreditBalanceData;
use App\Data\Rider\CreditTransactionData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Rider\GetCreditBalanceQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Rider')]
#[Endpoint('Get Credits', 'Get rider\'s current credit balance')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetCreditsController extends Controller
{
    public function __construct(
        private readonly GetCreditBalanceQueryInterface $query,
    ) {}

    public function __invoke(): JsonResponse
    {
        /** @var User $user */
        $user = request()->user();

        $transactions = $this->query->execute($user, 20);

        return ApiResponse::success(
            data: [
                'balance'      => CreditBalanceData::fromUser($user),
                'transactions' => CreditTransactionData::collect($transactions),
            ],
        );
    }
}
