<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Enums\RideStatus;
use App\Models\PromoCode;
use App\Models\PromoCodeUsage;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class ApplyPromoCodeAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function handle(User $user, Ride $ride, string $code): Ride
    {
        $promoCode = PromoCode::query()
            ->where('code', strtoupper($code))
            ->first();

        if (! $promoCode) {
            throw ValidationException::withMessages([
                'code' => [__('messages.promo.not_found')],
            ]);
        }

        if ($ride->status !== RideStatus::PENDING && $ride->status !== RideStatus::SCHEDULED) {
            throw ValidationException::withMessages([
                'ride' => [__('messages.promo.invalid_ride_status')],
            ]);
        }

        if (! $promoCode->isValid()) {
            throw ValidationException::withMessages([
                'code' => [__('messages.promo.invalid_or_expired')],
            ]);
        }

        if ($promoCode->hasBeenUsedByUser($user->id)) {
            throw ValidationException::withMessages([
                'code' => [__('messages.promo.already_used')],
            ]);
        }

        $orderAmount = $ride->estimated_price ?? 0;

        if ($orderAmount < $promoCode->min_order_amount) {
            throw ValidationException::withMessages([
                'code' => [__('messages.promo.minimum_not_met', ['minimum' => $promoCode->min_order_amount])],
            ]);
        }

        $userUsageCount = PromoCodeUsage::query()
            ->where('promo_code_id', $promoCode->id)
            ->where('user_id', $user->id)
            ->count();

        if ($userUsageCount >= $promoCode->usage_limit_per_user) {
            throw ValidationException::withMessages([
                'code' => [__('messages.promo.usage_limit_reached')],
            ]);
        }

        $discount = $promoCode->calculateDiscount($orderAmount);

        return $this->databaseManager->transaction(
            callback: function () use ($ride, $promoCode, $user, $discount): Ride {
                PromoCodeUsage::query()->create([
                    'promo_code_id'    => $promoCode->id,
                    'user_id'          => $user->id,
                    'ride_id'          => $ride->id,
                    'discount_applied' => $discount,
                ]);

                $promoCode->increment('used_count');

                $ride->update([
                    'discount_amount' => $discount,
                    'promo_code_id'   => $promoCode->id,
                ]);

                return $ride->refresh();
            },
            attempts: 3,
        );
    }
}
