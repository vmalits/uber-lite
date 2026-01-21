<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SupportTicketStatus;
use App\Policies\SupportTicketPolicy;
use Carbon\CarbonInterface;
use Database\Factories\SupportTicketFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $user_id
 * @property string $subject
 * @property string $message
 * @property SupportTicketStatus $status
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 */
#[UseFactory(SupportTicketFactory::class)]
#[UsePolicy(SupportTicketPolicy::class)]
class SupportTicket extends Model
{
    /** @use HasFactory<SupportTicketFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => SupportTicketStatus::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class);
    }
}
