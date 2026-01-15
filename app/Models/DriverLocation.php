<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DriverAvailabilityStatus;
use App\Observers\DriverLocationObserver;
use Carbon\CarbonInterface;
use Database\Factories\DriverLocationFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $id
 * @property-read string $driver_id
 * @property DriverAvailabilityStatus $status
 * @property float $lat
 * @property float $lng
 * @property CarbonInterface|null $last_active_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface $created_at
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
        'status',
        'lat',
        'lng',
        'location_point',
        'last_active_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status'         => DriverAvailabilityStatus::class,
            'lat'            => 'float',
            'lng'            => 'float',
            'last_active_at' => 'datetime',
        ];
    }

    public function isOnline(): bool
    {
        return $this->status === DriverAvailabilityStatus::ONLINE;
    }

    public function isOffline(): bool
    {
        return $this->status === DriverAvailabilityStatus::OFFLINE;
    }

    public function isBusy(): bool
    {
        return $this->status === DriverAvailabilityStatus::BUSY;
    }
}
