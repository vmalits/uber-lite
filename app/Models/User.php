<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/*
 * @property-read string $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property CarbonInterface|null $phone_verified_at
 * @property CarbonInterface|null $last_login_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasUlids;
    use Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'role',
        'phone_verified_at',
        'last_login_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
        ];
    }
}
