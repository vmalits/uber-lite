<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CreditTransactionType;
use Carbon\CarbonInterface;
use Database\Factories\CreditTransactionFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $user_id
 * @property int $amount
 * @property int $balance_after
 * @property CreditTransactionType $type
 * @property string $description
 * @property string|null $related_id
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read User $user
 */
#[UseFactory(CreditTransactionFactory::class)]
class CreditTransaction extends Model
{
    /** @use HasFactory<CreditTransactionFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'user_id',
        'amount',
        'balance_after',
        'type',
        'description',
        'related_id',
    ];

    protected function casts(): array
    {
        return [
            'amount'        => 'integer',
            'balance_after' => 'integer',
            'type'          => CreditTransactionType::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCredit(): bool
    {
        return $this->amount > 0;
    }

    public function isDebit(): bool
    {
        return $this->amount < 0;
    }
}
