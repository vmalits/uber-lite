<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\SupportTicketCommentFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $ticket_id
 * @property string $user_id
 * @property string $message
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read SupportTicket $ticket
 * @property-read User $user
 */
#[UseFactory(SupportTicketCommentFactory::class)]
class SupportTicketComment extends Model
{
    /** @use HasFactory<SupportTicketCommentFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    /**
     * @return BelongsTo<SupportTicket, $this>
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(related: SupportTicket::class, foreignKey: 'ticket_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(related: User::class, foreignKey: 'user_id');
    }
}
