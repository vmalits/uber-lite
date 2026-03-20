<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Enums\CreditTransactionType;
use App\Models\CreditTransaction;
use App\Models\WalletTopUp;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class CompleteWalletTopUpAction
{
    /**
     * @throws Throwable
     */
    public function handle(WalletTopUp $topUp): void
    {
        DB::transaction(function () use ($topUp): void {
            $user = DB::table('users')
                ->where('id', $topUp->user_id)
                ->lockForUpdate()
                ->first();

            if ($user === null) {
                return;
            }

            $currentBalance = \is_int($user->credits_balance ?? null)
                ? $user->credits_balance
                : 0;
            $newBalance = $currentBalance + $topUp->amount;

            DB::table('users')
                ->where('id', $topUp->user_id)
                ->update(['credits_balance' => $newBalance]);

            $topUp->markAsCompleted();

            CreditTransaction::query()->create([
                'user_id'       => $topUp->user_id,
                'amount'        => $topUp->amount,
                'balance_after' => $newBalance,
                'type'          => CreditTransactionType::WALLET_TOP_UP,
                'description'   => 'Wallet top-up',
                'related_id'    => $topUp->id,
            ]);
        });
    }
}
