<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AnnouncementTarget;
use Carbon\CarbonInterface;
use Database\Factories\AnnouncementFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read string $id
 * @property string $admin_id
 * @property string $title
 * @property string $body
 * @property AnnouncementTarget $target
 * @property bool $is_active
 * @property CarbonInterface|null $published_at
 * @property CarbonInterface|null $expires_at
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $admin
 */
#[UseFactory(AnnouncementFactory::class)]
final class Announcement extends Model
{
    /** @use HasFactory<AnnouncementFactory> */
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'admin_id',
        'title',
        'body',
        'target',
        'is_active',
        'published_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'target'       => AnnouncementTarget::class,
            'is_active'    => 'boolean',
            'published_at' => 'datetime',
            'expires_at'   => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
