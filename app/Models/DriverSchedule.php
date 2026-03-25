<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\DriverScheduleFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $driver_id
 * @property int $day_of_week
 * @property string $start_time
 * @property string $end_time
 * @property bool $enabled
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
#[UseFactory(DriverScheduleFactory::class)]
final class DriverSchedule extends Model
{
    /** @use HasFactory<DriverScheduleFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'driver_id',
        'day_of_week',
        'start_time',
        'end_time',
        'enabled',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'enabled'     => 'boolean',
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
