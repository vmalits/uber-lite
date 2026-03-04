<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentMethodType;
use App\Enums\PaymentProvider;
use Carbon\CarbonInterface;
use Database\Factories\PaymentMethodFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $user_id
 * @property PaymentMethodType $type
 * @property PaymentProvider $provider
 * @property string|null $provider_token
 * @property string|null $last_four
 * @property string|null $card_brand
 * @property int|null $expiry_month
 * @property int|null $expiry_year
 * @property string|null $holder_name
 * @property bool $is_default
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 */
#[UseFactory(PaymentMethodFactory::class)]
class PaymentMethod extends Model
{
    /** @use HasFactory<PaymentMethodFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'type',
        'provider',
        'provider_token',
        'last_four',
        'card_brand',
        'expiry_month',
        'expiry_year',
        'holder_name',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'type'          => PaymentMethodType::class,
            'provider'      => PaymentProvider::class,
            'is_default'    => 'boolean',
            'expiry_month'  => 'integer',
            'expiry_year'   => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function isExpired(): bool
    {
        if ($this->expiry_year === null || $this->expiry_month === null) {
            return false;
        }

        $expiryDate = now()
            ->setYear((int) $this->expiry_year)
            ->setMonth((int) $this->expiry_month)
            ->endOfMonth();

        return $expiryDate->isPast();
    }

    public function isCard(): bool
    {
        return $this->type === PaymentMethodType::CARD;
    }
}
