<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\FavoriteDriverPolicy;
use Carbon\CarbonInterface;
use Database\Factories\FavoriteDriverFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $driver_id
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 * @property-read User $driver
 */
#[UseFactory(FavoriteDriverFactory::class)]
#[UsePolicy(FavoriteDriverPolicy::class)]
class FavoriteDriver extends Model
{
    /** @use HasFactory<FavoriteDriverFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'driver_id',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'driver_id');
    }
}
