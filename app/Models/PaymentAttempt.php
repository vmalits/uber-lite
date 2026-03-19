<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Currency;
use App\Enums\PaymentStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $user_id
 * @property string $ride_id
 * @property string|null $payment_method_id
 * @property PaymentStatus $status
 * @property int $amount
 * @property int $credits_used
 * @property int $card_amount
 * @property Currency $currency
 * @property string|null $provider
 * @property string|null $provider_transaction_id
 * @property string|null $failure_reason
 * @property array<string, mixed>|null $metadata
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface|null $completed_at
 * @property-read User $user
 * @property-read Ride $ride
 * @property-read PaymentMethod|null $paymentMethod
 */
final class PaymentAttempt extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentAttemptFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'ride_id',
        'payment_method_id',
        'status',
        'amount',
        'credits_used',
        'card_amount',
        'currency',
        'provider',
        'provider_transaction_id',
        'failure_reason',
        'metadata',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => PaymentStatus::class,
            'currency'     => Currency::class,
            'amount'       => 'integer',
            'credits_used' => 'integer',
            'card_amount'  => 'integer',
            'metadata'     => 'array',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Ride, $this>
     */
    public function ride(): BelongsTo
    {
        return $this->belongsTo(Ride::class);
    }

    /**
     * @return BelongsTo<PaymentMethod, $this>
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === PaymentStatus::COMPLETED;
    }

    public function markCompleted(?string $providerTransactionId = null): void
    {
        $this->update([
            'status'                  => PaymentStatus::COMPLETED,
            'provider_transaction_id' => $providerTransactionId,
            'completed_at'            => now(),
        ]);
    }

    public function markFailed(string $reason): void
    {
        $this->update([
            'status'         => PaymentStatus::FAILED,
            'failure_reason' => $reason,
            'completed_at'   => now(),
        ]);
    }
}
