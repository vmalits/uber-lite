<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\BlockedDriverPolicy;
use Carbon\CarbonInterface;
use Database\Factories\BlockedDriverFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $rider_id
 * @property string $driver_id
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $rider
 * @property-read User $driver
 */
#[UseFactory(BlockedDriverFactory::class)]
#[UsePolicy(BlockedDriverPolicy::class)]
class BlockedDriver extends Model
{
    /** @use HasFactory<BlockedDriverFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'rider_id',
        'driver_id',
    ];

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
