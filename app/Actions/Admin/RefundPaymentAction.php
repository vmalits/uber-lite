<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Enums\CreditTransactionType;
use App\Enums\PaymentStatus;
use App\Models\CreditTransaction;
use App\Models\PaymentAttempt;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class RefundPaymentAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(PaymentAttempt $paymentAttempt, string $reason): PaymentAttempt
    {
        if ($paymentAttempt->status !== PaymentStatus::COMPLETED) {
            throw ValidationException::withMessages([
                'payment' => 'Only completed payments can be refunded.',
            ]);
        }

        return $this->databaseManager->transaction(
            callback: function () use ($paymentAttempt, $reason): PaymentAttempt {
                $creditsUsed = $paymentAttempt->credits_used;

                if ($creditsUsed > 0) {
                    /** @var User $user */
                    $user = $paymentAttempt->user;

                    $user->increment('credits_balance', $creditsUsed);
                    $user->refresh();

                    CreditTransaction::query()->create([
                        'user_id'       => $user->id,
                        'amount'        => $creditsUsed,
                        'balance_after' => $user->credits_balance,
                        'type'          => CreditTransactionType::REFUND,
                        'description'   => $reason,
                        'related_id'    => $paymentAttempt->id,
                    ]);
                }

                $paymentAttempt->update([
                    'status'         => PaymentStatus::REFUNDED,
                    'failure_reason' => $reason,
                ]);

                return $paymentAttempt->refresh();
            },
            attempts: 3,
        );
    }
}
