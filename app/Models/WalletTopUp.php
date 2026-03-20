<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Currency;
use App\Enums\WalletTopUpStatus;
use Carbon\CarbonInterface;
use Database\Factories\WalletTopUpFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $user_id
 * @property string|null $payment_method_id
 * @property WalletTopUpStatus $status
 * @property int $amount
 * @property Currency $currency
 * @property string|null $payment_intent_id
 * @property string|null $failure_reason
 * @property CarbonInterface|null $completed_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 * @property-read PaymentMethod|null $paymentMethod
 */
#[UseFactory(WalletTopUpFactory::class)]
final class WalletTopUp extends Model
{
    /** @use HasFactory<WalletTopUpFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'payment_method_id',
        'status',
        'amount',
        'currency',
        'payment_intent_id',
        'failure_reason',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => WalletTopUpStatus::class,
            'currency'     => Currency::class,
            'amount'       => 'integer',
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
     * @return BelongsTo<PaymentMethod, $this>
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function isPending(): bool
    {
        return $this->status === WalletTopUpStatus::PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === WalletTopUpStatus::COMPLETED;
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status'       => WalletTopUpStatus::COMPLETED,
            'completed_at' => now(),
        ]);
    }

    public function markAsCancelled(?string $reason = null): void
    {
        $this->update([
            'status'         => WalletTopUpStatus::CANCELLED,
            'failure_reason' => $reason,
        ]);
    }

    public function markAsFailed(string $reason): void
    {
        $this->update([
            'status'         => WalletTopUpStatus::FAILED,
            'failure_reason' => $reason,
        ]);
    }
}
