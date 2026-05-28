<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Data\DateData;
use App\Data\User\UserData;
use App\Enums\Currency;
use App\Enums\PaymentStatus;
use App\Models\PaymentAttempt;
use App\Services\Avatar\AvatarUrlService;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class AdminPaymentData extends Data
{
    public function __construct(
        public string $id,
        public ?UserData $user,
        #[MapName('ride_id')]
        public string $rideId,
        public PaymentStatus $status,
        public int $amount,
        #[MapName('credits_used')]
        public int $creditsUsed,
        #[MapName('card_amount')]
        public int $cardAmount,
        public Currency $currency,
        public ?string $provider,
        #[MapName('provider_transaction_id')]
        public ?string $providerTransactionId,
        #[MapName('failure_reason')]
        public ?string $failureReason,
        #[MapName('completed_at')]
        public ?DateData $completedAt,
        #[MapName('created_at')]
        public DateData $createdAt,
    ) {}

    public static function fromModel(PaymentAttempt $payment, AvatarUrlService $avatarResolver): self
    {
        $user = $payment->relationLoaded('user') ? $payment->user : null;

        return new self(
            id: $payment->id,
            user: $user !== null ? UserData::fromModel($user, $avatarResolver) : null,
            rideId: $payment->ride_id,
            status: $payment->status,
            amount: $payment->amount,
            creditsUsed: $payment->credits_used,
            cardAmount: $payment->card_amount,
            currency: $payment->currency,
            provider: $payment->provider,
            providerTransactionId: $payment->provider_transaction_id,
            failureReason: $payment->failure_reason,
            completedAt: $payment->completed_at ? DateData::fromCarbon($payment->completed_at) : null,
            createdAt: DateData::fromCarbon($payment->created_at),
        );
    }
}
