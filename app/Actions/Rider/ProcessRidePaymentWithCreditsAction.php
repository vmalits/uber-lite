<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\RidePaymentResultData;
use App\Enums\CreditTransactionType;
use App\Enums\Currency;
use App\Enums\PaymentStatus;
use App\Enums\RideStatus;
use App\Models\CreditTransaction;
use App\Models\PaymentAttempt;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final readonly class ProcessRidePaymentWithCreditsAction
{
    public function handle(User $user, Ride $ride): RidePaymentResultData
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

        $finalPrice = $ride->finalPrice();

        if ($user->credits_balance < $finalPrice) {
            throw ValidationException::withMessages([
                'credits' => 'Insufficient credits balance. You have :balance MDL but need :amount MDL.',
            ]);
        }

        return DB::transaction(function () use ($user, $ride, $finalPrice): RidePaymentResultData {
            $newBalance = $user->credits_balance - $finalPrice;

            $user->update(['credits_balance' => $newBalance]);

            CreditTransaction::query()->create([
                'user_id'       => $user->id,
                'amount'        => -$finalPrice,
                'balance_after' => $newBalance,
                'type'          => CreditTransactionType::RIDE_PAYMENT,
                'description'   => "Full credit payment for ride #{$ride->id}",
                'related_id'    => $ride->id,
            ]);

            $attempt = PaymentAttempt::query()->create([
                'user_id'      => $user->id,
                'ride_id'      => $ride->id,
                'status'       => PaymentStatus::COMPLETED,
                'amount'       => $finalPrice,
                'credits_used' => $finalPrice,
                'card_amount'  => 0,
                'currency'     => Currency::MDL,
                'completed_at' => now(),
            ]);

            return new RidePaymentResultData(
                payment_attempt_id: $attempt->id,
                status: PaymentStatus::COMPLETED,
                amount_paid: $finalPrice,
                credits_used: $finalPrice,
                card_charged: 0,
                remaining_balance: $newBalance,
                fully_paid: true,
            );
        });
    }
}
