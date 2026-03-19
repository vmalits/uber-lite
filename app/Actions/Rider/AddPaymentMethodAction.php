<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Data\Rider\AddPaymentMethodData;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Support\Facades\DB;

final readonly class AddPaymentMethodAction
{
    public function __construct(
        private PaymentServiceInterface $paymentService,
    ) {}

    public function handle(User $user, AddPaymentMethodData $data): PaymentMethod
    {
        $tokenized = $this->paymentService->tokenizeCard(
            token: $data->token,
            lastFour: $data->last_four,
            cardBrand: $data->card_brand,
            holderName: $data->holder_name,
        );

        return DB::transaction(function () use ($user, $data, $tokenized): PaymentMethod {
            $hasDefault = PaymentMethod::query()
                ->where('user_id', $user->id)
                ->where('is_default', true)
                ->exists();

            return PaymentMethod::create([
                'user_id'        => $user->id,
                'type'           => $data->type,
                'provider'       => $data->provider,
                'provider_token' => $tokenized->providerToken,
                'last_four'      => $tokenized->lastFour,
                'card_brand'     => $tokenized->cardBrand,
                'expiry_month'   => $data->expiry_month ?? $tokenized->expiryMonth,
                'expiry_year'    => $data->expiry_year ?? $tokenized->expiryYear,
                'holder_name'    => $data->holder_name ?? $tokenized->holderName,
                'is_default'     => ! $hasDefault,
            ]);
        });
    }
}
