<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class SetDefaultPaymentMethodAction
{
    public function handle(User $user, PaymentMethod $paymentMethod): PaymentMethod
    {
        return DB::transaction(function () use ($user, $paymentMethod): PaymentMethod {
            PaymentMethod::query()
                ->where('user_id', $user->id)
                ->where('is_default', true)
                ->update(['is_default' => false]);

            $paymentMethod->update(['is_default' => true]);

            /** @var PaymentMethod $fresh */
            $fresh = $paymentMethod->fresh();

            return $fresh;
        });
    }
}
