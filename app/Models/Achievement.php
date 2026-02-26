<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AchievementCategory;
use Carbon\CarbonInterface;
use Database\Factories\AchievementFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read string $id
 * @property string $name
 * @property string $key
 * @property string|null $description
 * @property string|null $icon
 * @property AchievementCategory $category
 * @property int $target_value
 * @property int $points_reward
 * @property array<string, mixed>|null $metadata
 * @property bool $is_active
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read Collection<int, UserAchievement> $userAchievements
 *
 * @method Builder<static> active()
 * @method Builder<static> forCategory(AchievementCategory $category)
 * @method Builder<static> forDrivers()
 * @method Builder<static> forRiders()
 */
#[UseFactory(AchievementFactory::class)]
class Achievement extends Model
{
    /** @use HasFactory<AchievementFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'name',
        'key',
        'description',
        'icon',
        'category',
        'target_value',
        'points_reward',
        'metadata',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'category'      => AchievementCategory::class,
            'target_value'  => 'integer',
            'points_reward' => 'integer',
            'metadata'      => 'array',
            'is_active'     => 'boolean',
        ];
    }

    /**
     * @return HasMany<UserAchievement, $this>
     */
    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    /**
     * @param Builder<static> $query
     *
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @param Builder<static> $query
     *
     * @return Builder<static>
     */
    public function scopeForCategory(Builder $query, AchievementCategory $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * @param Builder<static> $query
     *
     * @return Builder<static>
     */
    public function scopeForDrivers(Builder $query): Builder
    {
        return $query->whereIn('category', [AchievementCategory::DRIVER, AchievementCategory::COMMON]);
    }

    /**
     * @param Builder<static> $query
     *
     * @return Builder<static>
     */
    public function scopeForRiders(Builder $query): Builder
    {
        return $query->whereIn('category', [AchievementCategory::RIDER, AchievementCategory::COMMON]);
    }
}
