<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\RideMessagePolicy;
use Carbon\CarbonInterface;
use Database\Factories\RideMessageFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $ride_id
 * @property string $sender_id
 * @property string $message
 * @property CarbonInterface|null $read_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read Ride $ride
 * @property-read User $sender
 */
#[UsePolicy(RideMessagePolicy::class)]
class RideMessage extends Model
{
    /** @use HasFactory<RideMessageFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'ride_id',
        'sender_id',
        'message',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Ride, $this>
     */
    public function ride(): BelongsTo
    {
        return $this->belongsTo(related: Ride::class, foreignKey: 'ride_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'sender_id');
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}
