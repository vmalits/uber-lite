<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\DriverBanBuilder;
use Carbon\CarbonInterface;
use Database\Factories\DriverBanFactory;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $driver_id
 * @property string|null $banned_by
 * @property string $ban_source
 * @property string $ban_type
 * @property string $reason
 * @property string|null $external_id
 * @property CarbonInterface|null $expires_at
 * @property CarbonInterface|null $unbanned_at
 * @property string|null $unbanned_by
 * @property string|null $unban_reason
 * @property-read User $driver
 * @property-read User|null $bannedBy
 * @property-read User|null $unbannedBy
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
#[UseEloquentBuilder(DriverBanBuilder::class)]
class DriverBan extends Model
{
    /**  @use HasFactory<DriverBanFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'driver_id',
        'banned_by',
        'ban_source',
        'ban_type',
        'reason',
        'external_id',
        'expires_at',
        'unbanned_at',
        'unbanned_by',
        'unban_reason',
    ];

    protected function casts(): array
    {
        return [
            'expires_at'  => 'datetime',
            'unbanned_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function bannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function unbannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unbanned_by');
    }
}
