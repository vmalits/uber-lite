<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\RideBuilder;
use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Observers\RideObserver;
use Carbon\CarbonInterface;
use Database\Factories\RideFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read string $id
 * @property string $rider_id
 * @property string|null $driver_id
 * @property string $origin_address
 * @property float|null $origin_lat
 * @property float|null $origin_lng
 * @property string $destination_address
 * @property float|null $destination_lat
 * @property float|null $destination_lng
 * @property RideStatus $status
 * @property int|null $price
 * @property int|null $estimated_price
 * @property float|null $estimated_distance_km
 * @property float|null $estimated_duration_min
 * @property float|null $price_per_km
 * @property float|null $price_per_minute
 * @property float|null $base_fee
 * @property mixed $origin_point
 * @property mixed $destination_point
 * @property CarbonInterface|null $arrived_at
 * @property CarbonInterface|null $started_at
 * @property CarbonInterface|null $cancelled_at;
 * @property ActorType|null $cancelled_by_type
 * @property string|null $cancelled_by_id
 * @property string|null $cancelled_reason
 * @property CarbonInterface|null $completed_at
 * @property CarbonInterface|null $scheduled_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $rider
 * @property-read User|null $driver
 * @property-read RideRating|null $rating
 */
#[ObservedBy([RideObserver::class])]
#[UseEloquentBuilder(RideBuilder::class)]
#[UseFactory(RideFactory::class)]
class Ride extends Model
{
    /** @use HasFactory<RideFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'rider_id',
        'driver_id',
        'origin_address',
        'origin_lat',
        'origin_lng',
        'destination_address',
        'destination_lat',
        'destination_lng',
        'status',
        'price',
        'estimated_price',
        'estimated_distance_km',
        'estimated_duration_min',
        'price_per_km',
        'price_per_minute',
        'base_fee',
        'origin_point', // geography(Point, 4326)
        'destination_point',
        'arrived_at',
        'started_at',
        'cancelled_at',
        'cancelled_by_type',
        'cancelled_by_id',
        'cancelled_reason',
        'completed_at',
        'scheduled_at',
    ];

    protected function casts(): array
    {
        return [
            'origin_lat'             => 'float',
            'origin_lng'             => 'float',
            'destination_lat'        => 'float',
            'destination_lng'        => 'float',
            'status'                 => RideStatus::class,
            'price'                  => 'integer',
            'estimated_price'        => 'integer',
            'estimated_distance_km'  => 'float',
            'estimated_duration_min' => 'float',
            'price_per_km'           => 'float',
            'price_per_minute'       => 'float',
            'base_fee'               => 'float',
            'arrived_at'             => 'datetime',
            'started_at'             => 'datetime',
            'cancelled_by_type'      => ActorType::class,
            'cancelled_at'           => 'datetime',
            'completed_at'           => 'datetime',
            'scheduled_at'           => 'datetime',
        ];
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

    /**
     * @return HasOne<RideRating, $this>
     */
    public function rating(): HasOne
    {
        return $this->hasOne(related: RideRating::class);
    }

    public function canUpdateRating(RideRating $rating): bool
    {
        return $rating->updated_at->diffInHours(now()) >= 24;
    }
}
