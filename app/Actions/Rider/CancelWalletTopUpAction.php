<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\WalletTopUp;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;

final readonly class CancelWalletTopUpAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private PaymentServiceInterface $paymentService,
    ) {}

    public function handle(WalletTopUp $topUp): void
    {
        if (! $topUp->isPending()) {
            throw ValidationException::withMessages([
                'top_up' => [__('messages.wallet.not_pending')],
            ]);
        }

        $this->databaseManager->transaction(function () use ($topUp): void {
            if ($topUp->payment_intent_id !== null) {
                $cancelled = $this->paymentService->cancelWalletTopUpIntent($topUp->payment_intent_id);

                if (! $cancelled) {
                    throw ValidationException::withMessages([
                        'payment' => [__('messages.wallet.cancel_failed')],
                    ]);
                }
            }

            $topUp->markAsCancelled();
        });
    }
}
