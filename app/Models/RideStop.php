<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\RideStopFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $ride_id
 * @property int $order
 * @property string $address
 * @property float|null $lat
 * @property float|null $lng
 * @property mixed $point
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read Ride $ride
 */
#[UseFactory(RideStopFactory::class)]
class RideStop extends Model
{
    /** @use HasFactory<RideStopFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'ride_id',
        'order',
        'address',
        'lat',
        'lng',
        'point',
    ];

    public function casts(): array
    {
        return [
            'order' => 'integer',
            'lat'   => 'float',
            'lng'   => 'float',
        ];
    }

    /**
     * @return BelongsTo<Ride, $this>
     */
    public function ride(): BelongsTo
    {
        return $this->belongsTo(related: Ride::class);
    }
}
