<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\WalletTopUpData;
use App\Models\WalletTopUp;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Validation\ValidationException;

final readonly class ConfirmWalletTopUpAction
{
    public function __construct(
        private CompleteWalletTopUpAction $completeAction,
        private PaymentServiceInterface $paymentService,
    ) {}

    /**
     * @throws ValidationException
     */
    public function handle(WalletTopUp $topUp): WalletTopUpData
    {
        if (! $topUp->isPending()) {
            throw ValidationException::withMessages([
                'top_up' => [__('messages.wallet.not_pending')],
            ]);
        }

        if ($topUp->payment_intent_id === null) {
            throw ValidationException::withMessages([
                'payment' => [__('messages.wallet.confirmation_failed')],
            ]);
        }

        $confirmed = $this->paymentService->confirmWalletTopUpIntent(
            $topUp->payment_intent_id,
        );

        if (! $confirmed) {
            throw ValidationException::withMessages([
                'payment' => [__('messages.wallet.confirmation_failed')],
            ]);
        }

        $this->completeAction->handle($topUp);

        return WalletTopUpData::fromModel($topUp->refresh());
    }
}
