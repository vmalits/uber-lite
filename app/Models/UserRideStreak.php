<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\UserRideStreakFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $user_id
 * @property int $current_streak
 * @property int $longest_streak
 * @property CarbonInterface|null $last_ride_date
 * @property CarbonInterface|null $streak_started_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 */
#[UseFactory(UserRideStreakFactory::class)]
class UserRideStreak extends Model
{
    /** @use HasFactory<UserRideStreakFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'current_streak',
        'longest_streak',
        'last_ride_date',
        'streak_started_at',
    ];

    protected function casts(): array
    {
        return [
            'current_streak'    => 'integer',
            'longest_streak'    => 'integer',
            'last_ride_date'    => 'date',
            'streak_started_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }

    public function isActive(): bool
    {
        return $this->current_streak > 0
            && $this->last_ride_date !== null
            && $this->last_ride_date->isAfter(now()->subDays(2));
    }
}
