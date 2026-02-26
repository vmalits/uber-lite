<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserTier;
use Carbon\CarbonInterface;
use Database\Factories\UserLevelFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $user_id
 * @property int $level
 * @property int $xp
 * @property UserTier $tier
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 */
#[UseFactory(UserLevelFactory::class)]
class UserLevel extends Model
{
    /** @use HasFactory<UserLevelFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'level',
        'xp',
        'tier',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'xp'    => 'integer',
            'tier'  => UserTier::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function xpToNextLevel(): ?int
    {
        return $this->tier->xpToNextLevel($this->xp);
    }

    public function nextTier(): ?UserTier
    {
        return $this->tier->nextTier();
    }

    public function recalculateTier(): void
    {
        $newTier = UserTier::fromXp($this->xp);

        if ($this->tier !== $newTier) {
            $this->tier = $newTier;
        }
    }

    public function addXp(int $points): bool
    {
        $oldTier = $this->tier;
        $this->xp += $points;
        $this->level = (int) floor($this->xp / 100) + 1;
        $this->recalculateTier();

        return $this->tier !== $oldTier;
    }
}
