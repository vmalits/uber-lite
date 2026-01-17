<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\FavoriteLocationFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $address
 * @property float $lat
 * @property float $lng
 * @property string $name
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 */
#[UseFactory(FavoriteLocationFactory::class)]
class FavoriteLocation extends Model
{
    /** @use HasFactory<FavoriteLocationFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'name',
        'lat',
        'lng',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lng' => 'float',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    }
}
