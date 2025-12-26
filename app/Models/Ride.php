<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\RideBuilder;
use App\Enums\RideStatus;
use App\Observers\RideObserver;
use Carbon\CarbonInterface;
use Database\Factories\RideFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property float|null $price
 * @property mixed $origin_point
 * @property mixed $destination_point
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $rider
 * @property-read User|null $driver
 */
#[ObservedBy([RideObserver::class])]
#[UseEloquentBuilder(RideBuilder::class)]
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
        'origin_point', // geography(Point, 4326)
        'destination_point',
    ];

    protected function casts(): array
    {
        return [
            'origin_lat'      => 'float',
            'origin_lng'      => 'float',
            'destination_lat' => 'float',
            'destination_lng' => 'float',
            'status'          => RideStatus::class,
            'price'           => 'float',
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
}
