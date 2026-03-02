<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\RideTipFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $ride_id
 * @property string $rider_id
 * @property string $driver_id
 * @property int $amount
 * @property string|null $comment
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read Ride $ride
 * @property-read User $rider
 * @property-read User $driver
 */
#[UseFactory(RideTipFactory::class)]
class RideTip extends Model
{
    /** @use HasFactory<RideTipFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'ride_id',
        'rider_id',
        'driver_id',
        'amount',
        'comment',
    ];

    public function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Ride, $this>
     */
    public function ride(): BelongsTo
    {
        return $this->belongsTo(related: Ride::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'rider_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'driver_id');
    }
}
