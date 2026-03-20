<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\WalletTopUpData;
use App\Enums\Currency;
use App\Enums\WalletTopUpStatus;
use App\Models\User;
use App\Models\WalletTopUp;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class CreateWalletTopUpAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private PaymentServiceInterface $paymentService,
    ) {}

    public const int MIN_AMOUNT = 50;

    public const int MAX_AMOUNT = 50000;

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function handle(User $user, int $amount, ?string $paymentMethodId = null): WalletTopUpData
    {
        if ($amount < self::MIN_AMOUNT) {
            throw ValidationException::withMessages([
                'amount' => [__('messages.wallet.min_amount', ['min' => self::MIN_AMOUNT])],
            ]);
        }

        if ($amount > self::MAX_AMOUNT) {
            throw ValidationException::withMessages([
                'amount' => [__('messages.wallet.max_amount', ['max' => self::MAX_AMOUNT])],
            ]);
        }

        $currency = Currency::MDL;

        $intentResult = $this->paymentService->createWalletTopUpIntent(
            amount: $amount,
            currency: $currency->value,
            paymentMethodId: $paymentMethodId,
            customerId: $user->id,
        );

        if (! $intentResult->isSuccessful()) {
            throw ValidationException::withMessages([
                'payment' => [$intentResult->errorMessage ?? __('messages.wallet.payment_failed')],
            ]);
        }

        return $this->databaseManager->transaction(
            callback: function () use ($user, $amount, $currency, $paymentMethodId, $intentResult): WalletTopUpData {
                $topUp = WalletTopUp::query()->create([
                    'user_id'           => $user->id,
                    'amount'            => $amount,
                    'currency'          => $currency,
                    'payment_method_id' => $paymentMethodId,
                    'payment_intent_id' => $intentResult->paymentIntentId,
                    'status'            => WalletTopUpStatus::PENDING,
                ]);

                return WalletTopUpData::fromModel($topUp, $intentResult->clientSecret);
            },
            attempts: 3,
        );
    }
}
