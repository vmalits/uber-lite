<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Enums\RideStatus;
use App\Models\PromoCodeUsage;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class RemovePromoCodeAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function handle(User $user, Ride $ride): Ride
    {
        if ($ride->status !== RideStatus::PENDING && $ride->status !== RideStatus::SCHEDULED) {
            throw ValidationException::withMessages([
                'ride' => [__('messages.promo.invalid_ride_status')],
            ]);
        }

        $usage = PromoCodeUsage::query()
            ->where('ride_id', $ride->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $usage) {
            throw ValidationException::withMessages([
                'code' => [__('messages.promo.not_found')],
            ]);
        }

        return $this->databaseManager->transaction(
            callback: function () use ($ride, $usage): Ride {
                $promoCode = $usage->promoCode;

                $usage->delete();

                $promoCode->decrement('used_count');

                $ride->update([
                    'discount_amount' => null,
                    'promo_code_id'   => null,
                ]);

                return $ride->refresh();
            },
            attempts: 3,
        );
    }
}
