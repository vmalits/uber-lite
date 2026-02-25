<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $promo_code_id
 * @property string $user_id
 * @property string $ride_id
 * @property int $discount_applied
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read PromoCode $promoCode
 * @property-read User $user
 * @property-read Ride $ride
 */
class PromoCodeUsage extends Model
{
    use HasUlids;

    protected $fillable = [
        'promo_code_id',
        'user_id',
        'ride_id',
        'discount_applied',
    ];

    protected function casts(): array
    {
        return [
            'discount_applied' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<PromoCode, $this>
     */
    public function promoCode(): BelongsTo
    {
        return $this->belongsTo(PromoCode::class);
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
}
