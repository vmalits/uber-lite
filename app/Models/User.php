<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Notifications\Auth\VerifyEmailNotification;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read string $id
 * @property string $phone
 * @property string|null $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property UserRole|null $role
 * @property CarbonInterface|null $phone_verified_at
 * @property CarbonInterface|null $email_verified_at
 * @property CarbonInterface|null $last_login_at
 * @property ProfileStep|null $profile_step
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use HasUlids;
    use Notifiable;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'phone',
        'email',
        'first_name',
        'last_name',
        'role',
        'phone_verified_at',
        'email_verified_at',
        'last_login_at',
        'profile_step',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'email_verified_at' => 'datetime',
            'profile_step'      => ProfileStep::class,
            'role'              => UserRole::class,
        ];
    }

    public function isDriver(): bool
    {
        return $this->role === UserRole::DRIVER;
    }

    public function isRider(): bool
    {
        return $this->role === UserRole::RIDER;
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification(
            userId: $this->id,
            emailHash: sha1($this->getEmailForVerification()),
        ));
    }
}
