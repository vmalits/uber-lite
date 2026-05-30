<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DriverDocumentStatus;
use App\Enums\DriverDocumentType;
use App\Policies\DriverDocumentPolicy;
use Carbon\CarbonInterface;
use Database\Factories\DriverDocumentFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $driver_id
 * @property string $type
 * @property string $file_path
 * @property string $original_name
 * @property string $mime_type
 * @property int $size
 * @property string $status
 * @property string|null $verified_by
 * @property string|null $rejection_reason
 * @property CarbonInterface|null $verified_at
 * @property CarbonInterface|null $expires_at
 * @property-read User $driver
 * @property-read User|null $verifier
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
#[UsePolicy(DriverDocumentPolicy::class)]
class DriverDocument extends Model
{
    /** @use HasFactory<DriverDocumentFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'driver_id',
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'status',
        'verified_by',
        'rejection_reason',
        'verified_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'size'        => 'integer',
            'type'        => DriverDocumentType::class,
            'status'      => DriverDocumentStatus::class,
            'verified_at' => 'datetime',
            'expires_at'  => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
