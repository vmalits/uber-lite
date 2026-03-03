<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PayoutMethod;
use App\Enums\PayoutStatus;
use Carbon\CarbonInterface;
use Database\Factories\DriverPayoutFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $driver_id
 * @property int $amount
 * @property PayoutStatus $status
 * @property PayoutMethod $method
 * @property string|null $bank_name
 * @property string|null $bank_account_number
 * @property string|null $bank_routing_number
 * @property string|null $crypto_wallet_address
 * @property string|null $crypto_currency
 * @property CarbonInterface|null $requested_at
 * @property CarbonInterface|null $approved_at
 * @property CarbonInterface|null $processed_at
 * @property CarbonInterface|null $completed_at
 * @property CarbonInterface|null $failed_at
 * @property string|null $failure_reason
 * @property string|null $description
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $driver
 */
#[UseFactory(DriverPayoutFactory::class)]
class DriverPayout extends Model
{
    /** @use HasFactory<DriverPayoutFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'driver_id',
        'amount',
        'status',
        'method',
        'bank_name',
        'bank_account_number',
        'bank_routing_number',
        'crypto_wallet_address',
        'crypto_currency',
        'requested_at',
        'approved_at',
        'processed_at',
        'completed_at',
        'failed_at',
        'failure_reason',
        'description',
    ];

    public function casts(): array
    {
        return [
            'amount'       => 'integer',
            'status'       => PayoutStatus::class,
            'method'       => PayoutMethod::class,
            'requested_at' => 'datetime',
            'approved_at'  => 'datetime',
            'processed_at' => 'datetime',
            'completed_at' => 'datetime',
            'failed_at'    => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'driver_id');
    }

    public function isPending(): bool
    {
        return $this->status === PayoutStatus::PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === PayoutStatus::COMPLETED;
    }
}
