<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DiscountType;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read string $id
 * @property string $code
 * @property string $title
 * @property string|null $description
 * @property DiscountType $discount_type
 * @property int $discount_value
 * @property int|null $max_discount_amount
 * @property int $min_order_amount
 * @property int|null $usage_limit
 * @property int $usage_limit_per_user
 * @property int $used_count
 * @property CarbonInterface|null $starts_at
 * @property CarbonInterface|null $expires_at
 * @property bool $is_active
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read Collection<int, PromoCodeUsage> $usages
 */
class PromoCode extends Model
{
    use HasUlids;

    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_order_amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_type'        => DiscountType::class,
            'discount_value'       => 'integer',
            'max_discount_amount'  => 'integer',
            'min_order_amount'     => 'integer',
            'usage_limit'          => 'integer',
            'usage_limit_per_user' => 'integer',
            'used_count'           => 'integer',
            'starts_at'            => 'datetime',
            'expires_at'           => 'datetime',
            'is_active'            => 'boolean',
        ];
    }

    /**
     * @return HasMany<PromoCodeUsage, $this>
     */
    public function usages(): HasMany
    {
        return $this->hasMany(PromoCodeUsage::class);
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function hasBeenUsedByUser(string $userId): bool
    {
        return $this->usages()
            ->where('user_id', $userId)
            ->exists();
    }

    public function calculateDiscount(int $orderAmount): int
    {
        if ($orderAmount < $this->min_order_amount) {
            return 0;
        }

        if ($this->discount_type === DiscountType::FIXED) {
            return min($this->discount_value, $orderAmount);
        }

        $discount = (int) round($orderAmount * $this->discount_value / 100);

        if ($this->max_discount_amount !== null) {
            $discount = min($discount, $this->max_discount_amount);
        }

        return min($discount, $orderAmount);
    }
}
