<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\UserAchievementFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $user_id
 * @property string $achievement_id
 * @property int $progress
 * @property CarbonInterface|null $completed_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 * @property-read Achievement $achievement
 *
 * @method Builder<static> completed()
 * @method Builder<static> inProgress()
 */
#[UseFactory(UserAchievementFactory::class)]
class UserAchievement extends Model
{
    /** @use HasFactory<UserAchievementFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'achievement_id',
        'progress',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'progress'     => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Achievement, $this>
     */
    public function achievement(): BelongsTo
    {
        return $this->belongsTo(Achievement::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function progressPercentage(): float
    {
        if ($this->achievement->target_value <= 0) {
            return 0.0;
        }

        return min(100.0, round(($this->progress / $this->achievement->target_value) * 100, 1));
    }

    /**
     * @param Builder<static> $query
     *
     * @return Builder<static>
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->whereNotNull('completed_at');
    }

    /**
     * @param Builder<static> $query
     *
     * @return Builder<static>
     */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->whereNull('completed_at');
    }
}
