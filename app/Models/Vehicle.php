<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\VehicleType;
use Carbon\CarbonInterface;
use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $driver_id
 * @property string $brand
 * @property string $model
 * @property int $year
 * @property string $color
 * @property string $plate_number
 * @property string $vehicle_type
 * @property int $seats
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
#[UseFactory(VehicleFactory::class)]
final class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'driver_id',
        'brand',
        'model',
        'year',
        'color',
        'plate_number',
        'vehicle_type',
        'seats',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'year'         => 'integer',
            'seats'        => 'integer',
            'vehicle_type' => VehicleType::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
