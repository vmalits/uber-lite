<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\NotificationPreferenceFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property bool $ride_updates
 * @property bool $promo
 * @property bool $ride_split
 * @property bool $achievement
 * @property bool $streak
 * @property bool $safety
 * @property bool $push_enabled
 * @property bool $email_enabled
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 */
#[UseFactory(NotificationPreferenceFactory::class)]
final class NotificationPreference extends Model
{
    /** @use HasFactory<NotificationPreferenceFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'ride_updates',
        'promo',
        'ride_split',
        'achievement',
        'streak',
        'safety',
        'push_enabled',
        'email_enabled',
    ];

    protected function casts(): array
    {
        return [
            'ride_updates'  => 'boolean',
            'promo'         => 'boolean',
            'ride_split'    => 'boolean',
            'achievement'   => 'boolean',
            'streak'        => 'boolean',
            'safety'        => 'boolean',
            'push_enabled'  => 'boolean',
            'email_enabled' => 'boolean',
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
