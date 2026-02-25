<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Enums\CreditTransactionType;
use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class AdjustUserCreditsAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(User $user, int $amount, string $description): CreditTransaction
    {
        return $this->databaseManager->transaction(
            callback: function () use ($user, $amount, $description): CreditTransaction {
                $user->increment('credits_balance', $amount);

                $user->refresh();

                /** @var CreditTransaction $transaction */
                $transaction = CreditTransaction::query()->create([
                    'user_id'       => $user->id,
                    'amount'        => $amount,
                    'balance_after' => $user->credits_balance,
                    'type'          => CreditTransactionType::ADMIN_ADJUSTMENT,
                    'description'   => $description,
                ]);

                return $transaction;
            },
            attempts: 3,
        );
    }
}
