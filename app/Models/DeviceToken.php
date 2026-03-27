<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DevicePlatform;
use Carbon\CarbonInterface;
use Database\Factories\DeviceTokenFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property DevicePlatform $platform
 * @property string $token
 * @property string|null $device_name
 * @property string|null $app_version
 * @property CarbonInterface|null $last_used_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 */
#[UseFactory(DeviceTokenFactory::class)]
final class DeviceToken extends Model
{
    /** @use HasFactory<DeviceTokenFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'platform',
        'token',
        'device_name',
        'app_version',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'platform'     => DevicePlatform::class,
            'last_used_at' => 'datetime',
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
