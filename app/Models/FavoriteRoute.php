<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FavoriteRouteType;
use App\Policies\FavoriteRoutePolicy;
use Carbon\CarbonInterface;
use Database\Factories\FavoriteRouteFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $origin_address
 * @property float $origin_lat
 * @property float $origin_lng
 * @property string $destination_address
 * @property float $destination_lat
 * @property float $destination_lng
 * @property FavoriteRouteType|null $type
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 */
#[UseFactory(FavoriteRouteFactory::class)]
#[UsePolicy(FavoriteRoutePolicy::class)]
class FavoriteRoute extends Model
{
    /** @use HasFactory<FavoriteRouteFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'name',
        'origin_address',
        'origin_lat',
        'origin_lng',
        'destination_address',
        'destination_lat',
        'destination_lng',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'origin_lat'      => 'float',
            'origin_lng'      => 'float',
            'destination_lat' => 'float',
            'destination_lng' => 'float',
            'type'            => FavoriteRouteType::class,
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
