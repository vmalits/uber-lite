<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\PayRideData;
use App\Data\Rider\RidePaymentResultData;
use App\Enums\CreditTransactionType;
use App\Enums\Currency;
use App\Enums\PaymentStatus;
use App\Enums\RideStatus;
use App\Models\CreditTransaction;
use App\Models\PaymentAttempt;
use App\Models\PaymentMethod;
use App\Models\Ride;
use App\Models\User;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class ProcessRidePaymentAction
{
    public function __construct(
        private PaymentServiceInterface $paymentService,
    ) {}

    public function handle(User $user, Ride $ride, PayRideData $data): RidePaymentResultData
    {
        if ($ride->status !== RideStatus::COMPLETED) {
            throw ValidationException::withMessages([
                'ride' => 'Ride must be completed before payment.',
            ]);
        }

        if ($ride->isPaid()) {
            throw ValidationException::withMessages([
                'ride' => 'Ride is already paid.',
            ]);
        }

        $paymentMethod = PaymentMethod::query()->findOrFail($data->payment_method_id);

        if ($paymentMethod->user_id !== $user->id) {
            throw ValidationException::withMessages([
                'payment_method' => 'Payment method does not belong to you.',
            ]);
        }

        $finalPrice = $ride->finalPrice();
        $creditsUsed = $this->resolveCreditsToUse($user, $finalPrice, $data->credits_to_use);
        $cardAmount = max(0, $finalPrice - $creditsUsed);

        if ($finalPrice === 0) {
            return $this->createZeroPayment($user, $ride, $finalPrice);
        }

        return DB::transaction(function () use ($user, $ride, $paymentMethod, $finalPrice, $creditsUsed, $cardAmount): RidePaymentResultData {
            if ($creditsUsed > 0) {
                $this->debitCredits($user, $creditsUsed, $ride->id);
            }

            $attempt = PaymentAttempt::query()->create([
                'user_id'           => $user->id,
                'ride_id'           => $ride->id,
                'payment_method_id' => $paymentMethod->id,
                'status'            => PaymentStatus::PROCESSING,
                'amount'            => $finalPrice,
                'credits_used'      => $creditsUsed,
                'card_amount'       => $cardAmount,
                'currency'          => Currency::MDL,
                'provider'          => $paymentMethod->provider->value,
            ]);

            if ($cardAmount > 0) {
                $result = $this->paymentService->charge($attempt);

                if (! $result->successful) {
                    $attempt->markFailed($result->failureReason ?? 'Charge declined.');

                    throw ValidationException::withMessages([
                        'payment' => $attempt->failure_reason ?? 'Payment failed.',
                    ]);
                }

                $attempt->markCompleted($result->providerTransactionId);
            } else {
                $attempt->markCompleted('credits_only');
            }

            $user->refresh();

            return new RidePaymentResultData(
                payment_attempt_id: $attempt->id,
                status: $attempt->status,
                amount_paid: $finalPrice,
                credits_used: $creditsUsed,
                card_charged: $cardAmount,
                remaining_balance: $user->credits_balance,
                fully_paid: true,
            );
        });
    }

    private function resolveCreditsToUse(User $user, int $finalPrice, ?int $creditsToUse): int
    {
        if ($creditsToUse !== null && $creditsToUse > 0) {
            return min($creditsToUse, $user->credits_balance, $finalPrice);
        }

        return 0;
    }

    private function debitCredits(User $user, int $amount, string $rideId): void
    {
        $newBalance = $user->credits_balance - $amount;

        $user->update(['credits_balance' => $newBalance]);

        CreditTransaction::create([
            'user_id'       => $user->id,
            'amount'        => -$amount,
            'balance_after' => $newBalance,
            'type'          => CreditTransactionType::RIDE_PAYMENT,
            'description'   => "Payment for ride #{$rideId}",
            'related_id'    => $rideId,
        ]);
    }

    private function createZeroPayment(User $user, Ride $ride, int $finalPrice): RidePaymentResultData
    {
        $attempt = PaymentAttempt::create([
            'user_id'      => $user->id,
            'ride_id'      => $ride->id,
            'status'       => PaymentStatus::COMPLETED,
            'amount'       => $finalPrice,
            'credits_used' => 0,
            'card_amount'  => 0,
            'currency'     => Currency::MDL,
            'completed_at' => now(),
        ]);

        return new RidePaymentResultData(
            payment_attempt_id: $attempt->id,
            status: PaymentStatus::COMPLETED,
            amount_paid: $finalPrice,
            credits_used: 0,
            card_charged: 0,
            remaining_balance: $user->credits_balance,
            fully_paid: true,
        );
    }
}
