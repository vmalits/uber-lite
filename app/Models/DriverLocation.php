<?php

declare(strict_types=1);

namespace App\Models;

use App\Observers\DriverLocationObserver;
use Database\Factories\DriverLocationFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read string $id
 * @property-read string $driver_id
 * @property float $lat
 * @property float $lng
 * @property-read Carbon $updated_at
 * @property-read Carbon $created_at
 */
#[ObservedBy([DriverLocationObserver::class])]
#[UseFactory(DriverLocationFactory::class)]
class DriverLocation extends Model
{
    /**  @use HasFactory<DriverLocationFactory> */
    use HasFactory;
    use HasUlids;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'driver_id',
        'lat',
        'lng',
        'location_point',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lng' => 'float',
        ];
    }
}
