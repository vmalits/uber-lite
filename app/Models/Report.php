<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use Carbon\CarbonInterface;
use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $reporter_id
 * @property string $target_id
 * @property string|null $ride_id
 * @property ReportReason $reason
 * @property string|null $description
 * @property ReportStatus $status
 * @property string|null $admin_note
 * @property string|null $resolved_by
 * @property CarbonInterface|null $resolved_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $reporter
 * @property-read User $target
 * @property-read Ride|null $ride
 * @property-read User|null $resolver
 */
#[UseFactory(ReportFactory::class)]
final class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'reporter_id',
        'target_id',
        'ride_id',
        'reason',
        'description',
        'status',
        'admin_note',
        'resolved_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'reason'      => ReportReason::class,
            'status'      => ReportStatus::class,
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function target(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_id');
    }

    /**
     * @return BelongsTo<Ride, $this>
     */
    public function ride(): BelongsTo
    {
        return $this->belongsTo(Ride::class, 'ride_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
