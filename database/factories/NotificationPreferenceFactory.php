<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NotificationPreference>
 */
final class NotificationPreferenceFactory extends Factory
{
    protected $model = NotificationPreference::class;

    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'ride_updates'  => true,
            'promo'         => true,
            'ride_split'    => true,
            'achievement'   => true,
            'streak'        => true,
            'safety'        => true,
            'push_enabled'  => true,
            'email_enabled' => true,
        ];
    }
}
