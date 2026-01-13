<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Locale;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Notifications\Auth\VerifyEmailNotification;
use App\Observers\UserObserver;
use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property-read string $id
 * @property string $phone
 * @property string|null $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property array<string, string>|null $avatar_paths
 * @property UserRole|null $role
 * @property Locale|null $locale
 * @property CarbonInterface|null $phone_verified_at
 * @property CarbonInterface|null $email_verified_at
 * @property CarbonInterface|null $last_login_at
 * @property ProfileStep|null $profile_step
 * @property UserStatus $status
 * @property CarbonInterface|null $banned_at
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 */
#[ObservedBy([UserObserver::class])]
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
        'avatar_paths',
        'role',
        'locale',
        'phone_verified_at',
        'email_verified_at',
        'last_login_at',
        'profile_step',
        'status',
        'banned_at',
        'password',
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
            'banned_at'         => 'datetime',
            'status'            => UserStatus::class,
            'password'          => 'hashed',
            'avatar_paths'      => 'array',
            'locale'            => Locale::class,
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

    public function isPhoneVerified(): bool
    {
        return $this->phone_verified_at !== null || $this->profile_step === ProfileStep::PHONE_VERIFIED;
    }

    public function isProfileCompleted(): bool
    {
        return $this->profile_step === ProfileStep::COMPLETED;
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification(
            userId: $this->id,
            emailHash: sha1($this->getEmailForVerification()),
        ));
    }

    /**
     * @return HasMany<DriverBan, $this>
     */
    public function bans(): HasMany
    {
        return $this->hasMany(related: DriverBan::class, foreignKey: 'driver_id');
    }
}
