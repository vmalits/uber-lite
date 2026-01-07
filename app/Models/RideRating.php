<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\RideRatingFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $ride_id
 * @property string $rider_id
 * @property int $rating
 * @property string|null $comment
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read Ride $ride
 * @property-read User $rider
 */
#[UseFactory(RideRatingFactory::class)]
class RideRating extends Model
{
    /** @use HasFactory<RideRatingFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'ride_id',
        'rider_id',
        'rating',
        'comment',
    ];

    public function casts(): array
    {
        return [
            'rating' => 'int',
        ];
    }

    /**
     * @return BelongsTo<Ride, $this>
     */
    public function ride(): BelongsTo
    {
        return $this->belongsTo(related: Ride::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'rider_id');
    }

    public function canUpdateRating(): bool
    {
        return $this->updated_at->diffInHours(now()) >= 24;
    }
}
